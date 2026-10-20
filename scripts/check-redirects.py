#!/usr/bin/env python3
"""Checks wp/redirects.htaccess against the static site.

Every static .html file must match exactly one rule, and the target must
answer 200 on the WordPress site. Usage: python3 scripts/check-redirects.py [base-url]
"""
import re
import sys
import urllib.request
from pathlib import Path

root = Path(__file__).resolve().parent.parent
base = sys.argv[1] if len(sys.argv) > 1 else "http://lejwana.local"
rules = []
for line in (root / "wp/redirects.htaccess").read_text().splitlines():
    if line.startswith("RewriteRule ") and "R=301" in line:
        pattern, target = line.split()[1:3]
        rules.append((re.compile(pattern.replace("^", "^/", 1)), target))
    if line.startswith("RedirectMatch 301 "):
        pattern, target = line.split()[2:4]
        rules.append((re.compile(pattern), re.sub(r"\$(\d)", r"\\\1", target)))


def status(path):
    request = urllib.request.Request(base + path, method="HEAD", headers={"User-Agent": "Mozilla/5.0 (redirect check)"})
    try:
        return urllib.request.urlopen(request).status
    except urllib.error.HTTPError as error:
        return error.code


failures = 0
paths = ["/" + str(p.relative_to(root)) for p in sorted(root.glob("*.html")) + sorted(root.glob("the-southern-cut/*.html"))]
paths += ["/insight", "/insight.html", "/insight/on-yield", "/insight/on-yield.html"]
paths += [
    "/lejwana-gem-works/", "/contact-lejwana/", "/insights/", "/what-we-do/",
    "/what-we-do/diamond-brokerage-and-facilitation/", "/what-we-do/diamond-marketing-advisory/",
    "/what-we-do/industrial-services-and-consultancy/", "/what-we-do/cutting-and-polishing/",
    "/what-we-do/jewellery-manufacturing/", "/what-we-do/unique-buying-experience/",
    "/privacy-policy/", "/cookie-policy/", "/trends-and-innovations-in-diamond-valuation/",
    "/unveiling-botswanas-safari-inspired-jewelry-collection/", "/the-ethical-facets-of-diamond-sourcing/",
]
for path in paths:
    hits = [(rule, target) for rule, target in rules if rule.match(path)]
    if len(hits) != 1:
        print(f"✗ {path}: {len(hits)} rules match")
        failures += 1
        continue
    rule, target = hits[0]
    destination = rule.sub(target, path)
    code = status(destination)
    if code != 200:
        print(f"✗ {path} → {destination}: {code}")
        failures += 1
print(f"{len(paths) - failures}/{len(paths)} redirects resolve")
sys.exit(1 if failures else 0)
