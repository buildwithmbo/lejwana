#!/usr/bin/env python3
"""Headless QA loop: screenshot 1440px + 390px, capture console errors.

Usage: python3 scripts/review.py [url-or-path]  (default: index.html)
"""
import sys
from pathlib import Path
from playwright.sync_api import sync_playwright

target = sys.argv[1] if len(sys.argv) > 1 else "index.html"
if "://" not in target:
    target = Path(target).resolve().as_uri()

viewports = {"desktop": (1440, 900), "mobile": (390, 844)}
errors = []

with sync_playwright() as p:
    browser = p.chromium.launch()
    for name, (w, h) in viewports.items():
        page = browser.new_page(viewport={"width": w, "height": h})
        page.on("pageerror", lambda e: errors.append(f"pageerror: {e}"))
        page.on(
            "console",
            lambda m: errors.append(f"console.{m.type}: {m.text}")
            if m.type == "error"
            else None,
        )
        page.goto(target, wait_until="networkidle")
        # Scroll full height so every scroll-triggered effect fires.
        # scroll-behavior: smooth would animate these jumps, so the loop would
        # finish and return to the top before the page ever reached the bottom,
        # leaving lower sections untriggered. Force instant scrolling for the pass.
        page.evaluate(
            """async () => {
              const root = document.documentElement;
              const previous = root.style.scrollBehavior;
              root.style.scrollBehavior = 'auto';
              await new Promise((done) => {
                let y = 0;
                const step = () => {
                  y += 300;
                  window.scrollTo(0, y);
                  if (y < document.body.scrollHeight) setTimeout(step, 60);
                  else { window.scrollTo(0, 0); setTimeout(done, 400); }
                };
                step();
              });
              root.style.scrollBehavior = previous;
            }"""
        )
        page.screenshot(path=f"screenshots/{name}.png", full_page=True)
        page.close()
    browser.close()

Path("screenshots").mkdir(exist_ok=True)
if errors:
    print("✗ Errors found:")
    for e in errors:
        print("  " + e)
    sys.exit(1)
print("✓ Zero console/page errors. Screenshots in screenshots/.")
