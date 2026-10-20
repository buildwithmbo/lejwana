#!/usr/bin/env python3
"""Measure every rendered text pairing against WCAG AA, from real pixels.

Walks each page, takes the computed colour of every text-bearing element and
the effective background behind it (first non-transparent ancestor, alpha
composited), and reports anything under 4.5:1 — 3:1 where the text is large.
Also focuses every focusable element and measures its outline against the
ground it is drawn on.

Usage: python3 contrast_audit.py [page.html ...]
"""
import sys
from pathlib import Path
from playwright.sync_api import sync_playwright

PROBE = r"""() => {
  const parseRGB = (s) => {
    const m = s.match(/rgba?\(([^)]+)\)/);
    if (!m) return null;
    const p = m[1].split(',').map(Number);
    return { r: p[0], g: p[1], b: p[2], a: p.length > 3 ? p[3] : 1 };
  };
  const over = (fg, bg) => ({
    r: fg.r * fg.a + bg.r * (1 - fg.a),
    g: fg.g * fg.a + bg.g * (1 - fg.a),
    b: fg.b * fg.a + bg.b * (1 - fg.a),
    a: 1,
  });
  const lum = (c) => {
    const f = (v) => {
      v /= 255;
      return v <= 0.04045 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4);
    };
    return 0.2126 * f(c.r) + 0.7152 * f(c.g) + 0.0722 * f(c.b);
  };
  const ratio = (a, b) => {
    const [x, y] = [lum(a), lum(b)].sort((m, n) => n - m);
    return (x + 0.05) / (y + 0.05);
  };
  const hex = (c) =>
    '#' + [c.r, c.g, c.b].map((v) => Math.round(v).toString(16).padStart(2, '0').toUpperCase()).join('');

  // Effective background: composite every ancestor layer down to white.
  // A gradient or image is reported so it can be judged by hand rather than
  // silently treated as transparent.
  const ground = (el) => {
    const layers = [];
    let node = el;
    let painted = null;
    while (node && node !== document.documentElement.parentNode) {
      const cs = getComputedStyle(node);
      if (cs.backgroundImage && cs.backgroundImage !== 'none') painted = cs.backgroundImage;
      const bg = parseRGB(cs.backgroundColor);
      if (bg && bg.a > 0) {
        layers.push(bg);
        if (bg.a === 1) break;
      }
      node = node.parentElement;
    }
    let base = { r: 255, g: 255, b: 255, a: 1 };
    for (const l of layers.reverse()) base = over(l, base);
    return { colour: base, image: painted };
  };

  const results = [];
  window.__probe = [];
  const label = (el) => {
    const cls = (el.className && el.className.baseVal !== undefined ? el.className.baseVal : el.className) || '';
    return el.tagName.toLowerCase() + (cls ? '.' + String(cls).trim().split(/\s+/).join('.') : '');
  };

  for (const el of document.querySelectorAll('body *')) {
    const cs = getComputedStyle(el);
    if (cs.display === 'none' || cs.visibility === 'hidden' || parseFloat(cs.opacity) === 0) continue;
    const rect = el.getBoundingClientRect();
    if (!rect.width || !rect.height) continue;
    // Visually-hidden text is clipped to a pixel — it has no rendered ground.
    if (rect.width <= 1 || rect.height <= 1 || cs.clip === 'rect(0px, 0px, 0px, 0px)') continue;

    const text = Array.from(el.childNodes)
      .filter((n) => n.nodeType === 3)
      .map((n) => n.textContent.trim())
      .join(' ')
      .trim();
    if (!text) continue;

    // SVG text takes its ink from fill, not color — reading color here would
    // measure the inherited value and miss the label's real alpha.
    const isSVG = el.namespaceURI === 'http://www.w3.org/2000/svg';
    const fg = parseRGB(isSVG ? cs.fill : cs.color);
    if (!fg) continue;
    if (isSVG) {
      const own = parseFloat(cs.opacity);
      if (!Number.isNaN(own) && own < 1) fg.a *= own;
    }
    let g = ground(el);
    // A halo drawn under the fill (paint-order: stroke fill) IS the ground the
    // letterforms sit on — whatever passes behind the label never touches them.
    if (isSVG && cs.paintOrder && cs.paintOrder.startsWith('stroke')) {
      const halo = parseRGB(cs.stroke);
      if (halo && parseFloat(cs.strokeWidth) > 0) {
        g = { colour: halo.a < 1 ? over(halo, g.colour) : halo, image: null };
      }
    }
    const composited = fg.a < 1 ? over(fg, g.colour) : fg;
    const size = parseFloat(cs.fontSize);
    const weight = parseInt(cs.fontWeight, 10) || 400;
    const large = size >= 24 || (size >= 18.66 && weight >= 700);
    const need = large ? 3 : 4.5;
    const r = ratio(composited, g.colour);
    if (r < need) {
      results.push({
        kind: 'text',
        index: window.__probe.push(el) - 1,
        sel: label(el),
        fg: hex(composited),
        raw: [fg.r, fg.g, fg.b],
        alpha: fg.a,
        bg: hex(g.colour),
        image: g.image,
        ratio: Math.round(r * 100) / 100,
        need,
        size,
        sample: text.slice(0, 42),
      });
    }
  }

  // Focus rings: 3:1 against the ground the ring is drawn on (outside the
  // element, so the parent's background, not the element's own).
  const focusable = document.querySelectorAll('a[href], button, input, select, textarea, summary, [tabindex]');
  for (const el of focusable) {
    // preventScroll: focusing walks the whole page, and a scroll still in
    // flight when the next crop is taken samples the wrong part of the page.
    el.focus({ preventScroll: true });
    const cs = getComputedStyle(el);
    const ring = parseRGB(cs.outlineColor);
    const width = parseFloat(cs.outlineWidth);
    if (!ring || !width || cs.outlineStyle === 'none') continue;
    const g = ground(el.parentElement || el);
    const r = ratio(ring.a < 1 ? over(ring, g.colour) : ring, g.colour);
    if (r < 3) {
      results.push({
        kind: 'focus',
        index: window.__probe.push(el.parentElement || el) - 1,
        sel: label(el),
        fg: hex(ring),
        raw: [ring.r, ring.g, ring.b],
        alpha: ring.a,
        bg: hex(g.colour),
        image: g.image,
        ratio: Math.round(r * 100) / 100,
        need: 3,
        size: 0,
        sample: '(focus ring)',
      });
    }
    el.blur();
  }
  return results;
}"""

def srgb(v):
    v /= 255
    return v / 12.92 if v <= 0.04045 else ((v + 0.055) / 1.055) ** 2.4


def lum(c):
    return 0.2126 * srgb(c[0]) + 0.7152 * srgb(c[1]) + 0.0722 * srgb(c[2])


def ratio(a, b):
    x, y = sorted((lum(a), lum(b)), reverse=True)
    return (x + 0.05) / (y + 0.05)


def worst_ground(page, index, fg, need=4.5):
    """Re-measure against real pixels.

    A gradient or image ground has no backgroundColor to composite, so the DOM
    walk reports white. Hide the element's own ink, shoot the ground behind it,
    and take the pixel that contrasts least with the text — the ground varies
    across a gradient, so the worst pixel is the one that has to pass.

    Measured on the pixels the glyphs actually cover, found by shooting each
    text rect twice — ink visible, then ink hidden — and diffing. Anything that
    changed is glyph; the ground under it comes from the hidden shot. Sampling
    the whole rect instead would pick up whatever sits beside the text: a map
    label's rect overlaps its own ice gem, which is not the ground it sits on.
    """
    from io import BytesIO
    from PIL import Image

    rects = page.evaluate(
        """(i) => {
          const el = window.__probe[i];
          el.scrollIntoView({ block: 'center', behavior: 'instant' });
          const rects = [];
          for (const n of el.childNodes) {
            if (n.nodeType !== 3 || !n.textContent.trim()) continue;
            const range = document.createRange();
            range.selectNodeContents(n);
            rects.push(...Array.from(range.getClientRects()));
          }
          if (!rects.length) rects.push(el.getBoundingClientRect());
          // A clip that overhangs the viewport makes page.screenshot reject the
          // whole probe, so trim each rect to what is actually on screen.
          return rects.map((r) => {
            const x = Math.max(0, r.x);
            const y = Math.max(0, r.y);
            return {
              x,
              y,
              width: Math.min(r.right, window.innerWidth) - x,
              height: Math.min(r.bottom, window.innerHeight) - y,
            };
          });
        }""",
        index,
    )
    rects = [r for r in rects if r['width'] >= 1 and r['height'] >= 1 and r['y'] >= 0]
    if not rects:
        return None

    inked = [page.screenshot(clip=r) for r in rects]
    page.evaluate(
        """(i) => {
          const el = window.__probe[i];
          el.dataset.auditHidden = el.style.visibility;
          el.style.visibility = 'hidden';
        }""",
        index,
    )
    bare = [page.screenshot(clip=r) for r in rects]
    page.evaluate(
        """(i) => {
          const el = window.__probe[i];
          el.style.visibility = el.dataset.auditHidden || '';
          delete el.dataset.auditHidden;
        }""",
        index,
    )

    worst = None
    fallback = None
    total = 0
    under = 0
    for ink_shot, bare_shot in zip(inked, bare):
        ink = Image.open(BytesIO(ink_shot)).convert('RGB')
        ground = Image.open(BytesIO(bare_shot)).convert('RGB')
        if ink.size != ground.size:
            continue
        for a, b in zip(ink.getdata(), ground.getdata()):
            # Antialiased edges are part ink, part ground; only pixels the glyph
            # covers outright say what the type really sits on.
            if fallback is None or ratio(fg, b) < ratio(fg, fallback):
                fallback = b
            if sum(abs(x - y) for x, y in zip(a, b)) < 24:
                continue
            total += 1
            if ratio(fg, b) < need:
                under += 1
            if worst is None or ratio(fg, b) < ratio(fg, worst):
                worst = b
    # No glyph mask means the diff found nothing; fall back to the whole rect
    # rather than silently reporting the DOM walk's white.
    if worst is None:
        return fallback, 0
    return worst, (under / total if total else 0)


def to_rgb(h):
    h = h.lstrip('#')
    return tuple(int(h[i:i + 2], 16) for i in (0, 2, 4))


pages = sys.argv[1:] or [str(p) for p in sorted(Path('.').glob('*.html'))] + [
    str(p) for p in sorted(Path('the-southern-cut').glob('*.html'))
]

fails = 0
with sync_playwright() as p:
    browser = p.chromium.launch()
    for w, h in ((1440, 900), (390, 844)):
        page = browser.new_page(viewport={'width': w, 'height': h})
        for target in pages:
            page.goto(Path(target).resolve().as_uri(), wait_until='networkidle')
            # Settle the page before measuring: entrance transitions mid-flight
            # make the two probe screenshots disagree everywhere.
            page.add_style_tag(content='html { scroll-behavior: auto !important; } '
                               '*, *::before, *::after '
                               '{ transition: none !important; animation: none !important; }')
            page.evaluate("() => document.querySelectorAll('.reveal')"
                          ".forEach((el) => el.classList.add('is-visible'))")
            # Open every <details>: closed ones render no glyphs, so the
            # pixel diff finds nothing and reports white-on-white. The text
            # has to pass on the ground it shows on when opened.
            page.evaluate("() => document.querySelectorAll('details')"
                          ".forEach((el) => { el.open = true; })")
            for f in page.evaluate(PROBE):
                fg = to_rgb(f['fg'])
                measured = f['ratio']
                note = ''
                if f['image'] and f['index'] is not None:
                    result = worst_ground(page, f['index'], fg, f['need'])
                    if result and result[0]:
                        ground, share = result
                        # Translucent ink takes its colour from what it sits on,
                        # so composite it over the measured ground, not white.
                        ink = tuple(
                            f['raw'][c] * f['alpha'] + ground[c] * (1 - f['alpha'])
                            for c in range(3)
                        )
                        measured = round(ratio(ink, ground), 2)
                        note = (f' (ink #{"%02X%02X%02X" % tuple(round(v) for v in ink)}'
                                f' on #{"%02X%02X%02X" % ground}, {share:.1%} of glyph pixels under)')
                if measured >= f['need']:
                    continue
                fails += 1
                print(
                    f'{w}px {target:34} {f["sel"][:40]:42} '
                    f'{f["fg"]} on {f["bg"]}{note}  {measured:.2f} < {f["need"]}  "{f["sample"]}"'
                )
        page.close()
    browser.close()

print(f'\n{"✗ " + str(fails) + " pairings under AA" if fails else "✓ every rendered pairing passes AA"}')
sys.exit(1 if fails else 0)
