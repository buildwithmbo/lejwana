# LAUNCH.md — Natural Polished Diamonds · Southern Africa

Deployment target and everything that must be resolved before the site goes
live. The build is static and dependency-free: deploying is copying the repo
root (minus the gitignored internal files) to a host.

## Deployment

- **Host / DNS:** [CONFIRM: host and domain not yet chosen]
- **Deploy method:** [CONFIRM: not yet chosen — static host, any of Netlify/
  Cloudflare Pages/S3 works; no build step required]
- **Publish root:** repo root. `.gitignore` already excludes `docs/`,
  `CLAUDE.md`, `.claude/`, `scripts/`, `screenshots/` and all `*.md` from the
  public repo, so internal material never reaches the live domain.

## Blockers — must be resolved before launch

Each of these ships as a placeholder today. `[CONFIRM: …]` markers are in the
markup where the content belongs.

1. **Gaborone address** — placeholder in the footer of all 14 pages and in the
   Contact locations block. Markers: `[CONFIRM: full Gaborone street address]`.
2. **Cal.com link** — `contact.html` uses placeholder `lejwana/intro`. Replace
   with the real booking link. The embed is lazy-loaded on click, so the page
   stays fast; keep it that way.
3. **Form handler** — front-end validation only. Wire the form `action` to a
   mail handler (server endpoint or form service) and remove the stub success
   state in `js/main.js`. **The enquiry form does not currently send anything.**
   This is the one blocker that silently loses leads if missed. The form is
   now live on `contact.html` (2026-09-24 — was previously commented out
   entirely) with Sourcing's specification builder feeding into it via a
   query string, but this blocker is otherwise unchanged: nothing on either
   page sends anywhere until a handler is wired.
4. **Article dates** — the six launch articles carry placeholder monthly dates
   (Mar–Aug 2026). Set real publication dates.
5. **Wordmark** — currently an HTML/CSS lockup matching the brief (wide-tracked
   sans, sapphire, gold gem). Replace with the finalised SVG when supplied.
6. **Photography** — none ships. Slots are commented where the brief's approved
   subjects belong: home hero (`.hero--dark` ground) and the Insight featured
   panel (`.insight-featured__ground`). Approved subjects only — see DESIGN.md §7.
7. **Meta descriptions** — confirm per page; several are placeholders.
8. **Favicon + og image** — done: `images/favicon.svg` (diamond) and `images/og-image.jpg` (1200×630 crop of `about-hero.jpg`), tagged on all 24 pages. `og:image` and `og:url` use absolute `https://lejwana.com/` URLs.
9. **Analytics** — not included. Add if wanted, before launch.

## Open questions for the client

- **Page count** — the source brief says nine pages, the sitemap lists eight.
  Built to the eight-page sitemap. Confirm what the ninth was.
- **Self-hosted fonts** — currently Google Fonts. Self-host if zero third-party
  requests is a requirement (DESIGN.md §12).
- **CMS** — this is the static launch build. The Insight section is structured
  to map cleanly onto a CMS when one is chosen: title, category, standfirst,
  date, body, related and closing CTA are all discrete blocks, and the
  categories match the five approved ones.

## Sequence

1. QA.md complete — per-page checks and the pre-launch block
2. All `[CONFIRM: …]` markers resolved (`grep -rn CONFIRM . --include=*.html`)
3. Client sign-off
4. Deploy
5. Post-launch: run `scripts/review.py <live-url>`, submit sitemap if there is one

## Phase Two

Inventory and Toolkit are excluded from navigation and this build, per brief.
Neither enters navigation until live and maintained (DESIGN.md §11).
