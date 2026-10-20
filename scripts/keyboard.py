#!/usr/bin/env python3
"""Tabs through pages, checking every stop is visible and shows a focus style,
and that reduced-motion leaves no running animation or hidden reveal.

Usage: python3 scripts/keyboard.py url [url ...]
"""
import sys
from playwright.sync_api import sync_playwright

MAX_STOPS = 120
FOCUS_STYLE = """() => {
  const el = document.activeElement;
  if (!el || el === document.body) { return null; }
  const s = getComputedStyle(el);
  const r = el.getBoundingClientRect();
  const hasRing = (parseFloat(s.outlineWidth) > 0 && s.outlineStyle !== 'none') ||
    (s.boxShadow !== 'none') || el.matches(':focus-visible');
  return {
    tag: el.tagName.toLowerCase() + (el.className ? '.' + String(el.className).split(' ')[0] : ''),
    visible: r.width > 0 && r.height > 0 && s.visibility !== 'hidden',
    ring: hasRing && parseFloat(s.outlineWidth) > 0,
  };
}"""
MOTION = """() => ({
  running: document.getAnimations().filter(a => a.playState === 'running').map(a => a.animationName || a.constructor.name),
  hiddenReveals: [...document.querySelectorAll('.reveal')].filter(e => getComputedStyle(e).opacity !== '1').length,
})"""

problems = 0
with sync_playwright() as p:
    browser = p.chromium.launch()
    for url in sys.argv[1:]:
        page = browser.new_page(viewport={"width": 1440, "height": 900}, reduced_motion="reduce")
        page.goto(url, wait_until="networkidle")
        page.wait_for_timeout(600)
        stops, bad = 0, []
        for _ in range(MAX_STOPS):
            page.keyboard.press("Tab")
            info = page.evaluate(FOCUS_STYLE)
            if info is None:
                break
            stops += 1
            if not info["visible"] or not info["ring"]:
                bad.append(info["tag"])
        motion = page.evaluate(MOTION)
        flag = bad or motion["running"] or motion["hiddenReveals"]
        problems += bool(flag)
        print(("✗ " if flag else "✓ ") + f"{url}: {stops} tab stops", bad[:5] or "", motion["running"][:3] or "", motion["hiddenReveals"] or "")
        page.close()
    browser.close()
sys.exit(1 if problems else 0)
