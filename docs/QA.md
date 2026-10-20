# QA.md — Natural Polished Diamonds · Southern Africa

Run per section during build, then in full before launch.

## Per section
- [ ] Matches DESIGN.md (tokens, spacing, type)
- [ ] Copy matches CONTENT.md verbatim — no invented words
- [ ] Responsive at 390px and 1440px (scripts/review.py passes)
- [ ] Zero console/page errors
- [ ] Headings in order, one h1, landmarks correct
- [ ] Images: width, height, alt (decorative: alt="" aria-hidden)
- [ ] Keyboard: focus visible, logical order
- [ ] prefers-reduced-motion fallback works

## Pre-launch
- [ ] W3C HTML validation clean
- [ ] Lighthouse: performance + accessibility ≥ 90 mobile
- [ ] Meta title/description present ([CONFIRM] if not supplied)
- [x] Favicon + og image
- [ ] 404 behaviour checked
- [ ] All [CONFIRM: …] markers resolved
- [x] Contrast — every text pairing and focus ring passes AA on all 16 pages
      at both viewports (re-run 2026-09-17; two regressions since the 08-13
      sign-off fixed — DESIGN.md §13). One non-text item is open for the
      client: map gems at 2.77:1 on the crest

## Contrast

```
python3 scripts/contrast.py              # all 16 pages, 1440px and 390px
python3 scripts/contrast.py index.html   # one page
```

Exits non-zero and prints every failure as `ink on ground, ratio < required`.
Re-run it after any colour change — the token table cannot answer this on its
own, because a pairing only fails once you know which ground the element
actually lands on. Four of the seven failures it found were invisible to
ratio-by-ratio checking.

How it measures, so the numbers can be trusted:

- **Ink** comes from `fill` on SVG and `color` elsewhere, times the element's
  own opacity.
- **Ground** is the composited ancestor background. Where that is a gradient or
  an image there is no colour to composite, so it shoots the text's client
  rects twice — ink visible, then hidden — and diffs them. Pixels that changed
  are glyph; the ground under them comes from the hidden shot. The worst pixel
  is the one reported.
- **A halo counts as the ground.** Text drawn with `paint-order: stroke fill`
  sits on its own stroke, not on whatever passes behind it.
- Transitions are killed and `.reveal` resolved before probing. Measuring
  mid-entrance reports partial opacity and invents failures — it claimed 98% of
  the Windhoek label was under AA when nothing was wrong.

## Verification status — WordPress build, 2026-10-20

Local site (lejwana.local), theme and plugin at `a307b19` plus phase 7 changes.

| Check | Status |
|---|---|
| Lighthouse mobile, 8 pages (home, about, sourcing, contact, hub, events, insights, article) | ✅ Performance 94–99, accessibility 100, SEO 100 except hub 92 (see below). Run one page at a time: running alongside other browser work dropped the homepage to 73 |
| Best practices 78 | ⚠️ Only the local `http://` site (no HTTPS); re-run on staging |
| Hub SEO 92 | ⚠️ Two "Read more" links (copy from `CONTENT.md`; each has a descriptive `aria-label`, but Lighthouse reads the visible text) |
| Keyboard, 7 pages (`scripts/keyboard.py`) | ✅ 16–49 tab stops each, every stop visible with a focus ring |
| `prefers-reduced-motion`, same 7 pages | ✅ No running animation, no hidden `.reveal` |
| Redirect map (`wp/redirects.htaccess`, `scripts/check-redirects.py`) | ✅ 28 old URLs, one rule each, all targets answer 200 locally. Real `.htaccess` behaviour still to test on staging |
| Core CSS, emoji, oEmbed, REST and generator tags removed from the front end | ✅ `inc/performance.php` |
| 404 page | ✅ Renders, returns 404. Copy is `[CONFIRM]` |
| W3C validation (vnu) on the rendered PHP output, 25 pages (home, 8 pages, hub, events, insights, 12 articles, 404) | ✅ Zero errors 2026-10-20. Advisories only: WordPress's `<link … />` slashes in `wp_head`, `aria-hidden` on the hidden ticker, a section without a heading on `advisory`, a double hyphen in a comment on `contact` |
| Copy matches static pages (events, insights, 12 articles, JS off) | ✅ 2026-10-20; only straight-to-curly apostrophes differ |

## Verification status — 2026-10-19

What has actually been checked on the current build, and what has not. Anything
not listed as verified is **unverified**, not passing.

| Check | Status |
|---|---|
| One `<h1>` per page, all 24 pages | ✅ Verified 2026-10-19 |
| Zero console/page errors, all 24 pages | ✅ Verified 2026-10-19 |
| Desktop 1440px + mobile 390px layout | ✅ `index` and `privacy` inspected; no horizontal overflow on any of the 16 pages at 390px (2026-09-17) |
| Design tokens match DESIGN.md | ✅ Verified — palette, type scale, spacing, breakpoints all match `css/main.css` |
| Off-palette hexes limited to the four documented | ✅ Verified — `#16304f`, `#c6d4e2`, `#9b3535`, `#a8b88a` only |
| `prefers-reduced-motion` block present | ✅ Verified — kills smooth scroll + all transitions |
| Layout inspected by eye, remaining 14 pages | ⬜ Not run — only the overflow sweep covers them |
| Heading order (no skipped levels), all 24 pages | ✅ Verified 2026-10-19 — `insights` skipped h1 → h3, fixed |
| Contrast, all 24 pages at both viewports | ✅ Verified 2026-10-19 |
| Keyboard traversal, focus order | ✅ 6 pages tabbed through: every stop has a visible focus style, none lands on hidden content (2026-10-19) |
| W3C validation (vnu), all 24 pages | ✅ Zero errors 2026-10-19 — fixed a nested comment in `contact.html` and a bare `aria-labelledby` on a div in `the-southern-cut.html`; advisory warnings remain |
| Lighthouse (mobile) | ✅ After image optimisation 2026-10-19: `index` 94, `sourcing` 95, `trading` 96; `about` 89 (render-blocking CSS and fonts, not images). Accessibility 100 and SEO ≥ 90 on the five pages first tested |
| Form validation behaviour | ⬜ Not checked — and the form has no handler (LAUNCH.md blocker 3) |

Run the sweep with:

```
for f in *.html the-southern-cut/*.html; do python3 scripts/review.py "$f"; done
```

Note `scripts/review.py` overwrites `screenshots/desktop.png` and
`screenshots/mobile.png` each run — it keeps only the last page shot.
