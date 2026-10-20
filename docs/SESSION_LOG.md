# SESSION_LOG.md — Natural Polished Diamonds · Southern Africa

Newest entry first. Written by /wrap at the end of every session.

## 2026-10-20 (8) — WordPress phase 7: QA on the local site

`inc/performance.php` drops core block/emoji/oEmbed CSS and head tags.
`wp/redirects.htaccess` + `scripts/check-redirects.py` (28 old URLs, all
resolve locally). `scripts/keyboard.py` (tab order, focus ring, reduced
motion). **Results:** Lighthouse mobile 94–99 performance, 100 accessibility, SEO 100
(hub 92: two "Read more" links); keyboard and reduced motion clean on 7 pages;
vnu zero errors on 25 rendered pages. `brew install vnu` failed (openjdk needs
full Xcode), so vnu ran from the cached `vnu-jar` with a Temurin 21 JRE
downloaded to the scratchpad; nothing installed on the system.
**Not done:** best-practices 78 is HTTP-only (re-check on staging); the real
`.htaccess` redirect test; QA.md pre-launch boxes for [CONFIRM]s (38 + 404 copy).
**Next:** phase 8, handover (staging to production, Editor login, client guide,
backups). Needs SiteGround access and the 404 copy.

## 2026-10-20 (7) — WordPress phase 6: homepage ticker from events

`front-page.php` now renders the ticker from the next 8 upcoming events (end
date today or later) instead of the fixed list; `main.js` unchanged. With no
upcoming events the ticker markup is not output at all. Tested both ways (all
events set to draft, then restored): ticker present with 22 events, absent
with none, zero console errors either way. Events with no status note show
blank until JS fills it.
**Next:** phase 7, QA (Lighthouse, keyboard, vnu, redirect map, reduced motion).

## 2026-10-20 (6) — WordPress phase 5: content migrated

Added article search title/description fields to `lejwana-core` (`87866bb`), then
`scripts/migrate-content.php` (run with `wp eval-file`; idempotent). Local site
now holds 22 events (from `events.html`) and 12 articles (6 insights, 6
explainers) as block markup, with category, month date, excerpt (insight card
standfirst, or the explainer's lead line) and the original `<title>` and meta
description in the search fields. All 10 in-article `[CONFIRM]` comments kept.
Deleted the phase 2 test event "Diamond Expo".
**State:** with JS off, events, insights and all 12 article bodies, titles and
meta match the static pages. One difference: WordPress curls straight
apostrophes in 6 articles (e.g. "buyer's" → "buyer’s"); the rest of the site
already uses curly. Kept; flag if verbatim straight is wanted. Related articles
are automatic, so they differ from the static ones. Zero console errors.
**Next:** phase 6, homepage ticker from events.

## 2026-10-20 (5) — WordPress phase 4: Southern Cut templates built

Added `page-the-southern-cut.php` (hub), `page-events.php` (What's on),
`home.php` (Insights), `single.php` (article), `404.php`, `template-parts/`
(event row and table, intel item, insight card), `inc/editor.php` and
`editor.css`. Deviation from WORDPRESS.md: What's on and Insights are child
pages of the hub (`/the-southern-cut/events/`, `/insights/`), not an
`archive-lj_event.php`, so `lj_event` stays non-public. Articles live at
`/the-southern-cut/{slug}/` (permalink structure changed). Setup now creates
the hub, two child pages, five categories (incl. Explainers, kept out of
Insights). Block editor limited to paragraph, heading (h2/h3), list, quote.
Also fixed: no `<title>` tags on any page (added `title-tag` support); `main.js`
ticker no longer throws for an event with no link; `main.css` styles bare
`blockquote` in articles.
**State:** uncommitted. Tested with throwaway events/posts (since deleted):
filters work, heading order clean, zero console errors at 1440/390; the 404
logs the expected 404 resource error. Editor checked in the real block editor
(temp user, since deleted): styles match the front end, inserter offers only
paragraph/heading/list/quote, heading levels default to h2/h3. **Not run:** vnu
(no Java on this Mac; deferred to phase 7 by user).
**Flags:** 404 headline, message and meta are `[CONFIRM]` (no copy exists).
Static article meta descriptions and `<title>`s differ from the excerpt/h1;
templates use title and excerpt for now (decide in phase 5, maybe a meta field).
Empty event status note renders blank until JS fills it. Open [CONFIRM]s: 38 + 3 (404).
**Next:** phase 5, migration of events and six articles + explainers.

## 2026-10-20 (4) — WordPress phase 3: theme core built

Built `header.php`, `footer.php`, `front-page.php` and eight `page-{slug}.php`
templates from the static pages (copy verbatim, links via `lejwana_the_url()` /
`lejwana_the_asset()`). `functions.php` + `inc/` enqueue assets, set per-page
title/description/OG, and create the nine pages on theme activation. Shared
`css/`, `js/`, `images/` reach the theme via `scripts/sync-theme-assets.sh`
(assets folder gitignored; re-run after any change). Contact form now posts to
the plugin handler with server-rendered `?enquiry=` messages; `main.js` submits
natively when the form has an `action`. Added `.form__failure`.
**State:** commit `17f3cbf`. All nine pages match static at 1440/390 (diff
under 0.06%), zero console errors, test enquiry arrived in Mailpit. Not run:
vnu on PHP output. Exclude `/contact/` from SiteGround page cache (nonce).
Homepage ticker still static (phase 6). **Open [CONFIRM]s:** 38, unchanged.
**Next:** phase 4, the Southern Cut templates (hub, What's on, Insights, article, 404).

## 2026-10-20 (3) — WordPress phases 1 and 2 scaffolded on branch `wp`

Branch `wp` created. Phase 1: empty theme `wp/theme/lejwana/` and plugin
`wp/plugin/lejwana-core/`, symlinked into the Local site "lejwana" and
activated via WP-CLI (no PHP errors). Phase 2: `lj_event` post type, meta box
(start, end, type, link, place, status), `lj_country` taxonomy seeded with the
five countries, and the enquiry handler (nonce, honeypot, `wp_mail` to
`info@lejwana.com`). Tested via WP-CLI: sanitisers, valid send, honeypot, bad
nonce and type; a newline-injected email was mangled, so email is now validated raw.
**State:** commits `aa48adb`, `cb30787`. Not done: SiteGround fresh install and
staging; adding an event through the admin UI; test enquiry arriving by email;
`contact.html` not yet wired to the handler (phase 3, with `?enquiry=` messages).
**Open [CONFIRM]s:** unchanged, 38.
**Next:** in Local admin, add a test event to prove the meta box saves; then start phase 3.

## 2026-10-20 (2) — WordPress plan rewritten: whole site, no hybrid

No site code changed. `docs/WORDPRESS.md` rewritten after weighing subfolder,
subdomain and root-hybrid options: **everything moves to WordPress at once**
on SiteGround GoGeek (staging included), classic theme, native meta, no ACF.
Client edits events and articles only; the 9 other pages are developer-edited
templates. Homepage ticker rendered by the template (REST route dropped).
Contact form: custom handler in plugin `lejwana-core`, sends to
`info@lejwana.com`. Domain is at SiteGround, so no DNS transfer. Client
reviews copy before publishing. Corrected the [CONFIRM] count to 38 (was 42).
**State:** plan agreed, nothing built. Static site complete, 24 pages. QA open:
404, keyboard on 18 pages, eyeball on 14 pages. **Open [CONFIRM]s:** 38, as in
the entry below; the enquiry-email one is resolved. Unchecked: `info@lejwana.com` mailbox exists.
**Next:** phase 1 on user's go-ahead: LocalWP, `wp/theme/` and `wp/plugin/`, SiteGround staging.

## 2026-10-20 — WordPress plan agreed; 19 Oct QA work logged

No code changed today. `docs/WORDPRESS.md` (written 19 Oct) signed off after
the five open questions were answered: static site is **not** yet on
SiteGround (later dropped: whole site goes straight into WordPress), GoGeek plan (staging included),
hub copy developer-edited, related articles automatic by category (fallback
for single-post categories), client users added manually. Also logging 19 Oct,
never wrapped: W3C zero errors on 24 pages, heading-order fix on `insights`,
favicon + OG tags with absolute lejwana.com URLs, images recompressed and
lazy-loaded, Lighthouse 94–96 (`about` 89). `docs/` is gitignored, so these
docs are not in git history.
**State:** static build complete, 24 pages. QA open: 404 check, keyboard on 18
pages, eyeball pass on 14 pages, `about` Lighthouse. **Open [CONFIRM]s:** 38
across 24 files — Gaborone address (24 footers + contact), Cal.com link, newsletter
handler, explainers button, 10 explainer fact-checks.
**Next:** WordPress phase 1 — local setup and SiteGround staging (WORDPRESS.md revised: whole site in WP, no static move).

## 2026-10-10 — Events/Insights pages, Provenance made a fourth service

Event links in the calendar lost the `.text-link` underline (scoped to
`.whats-on__table`). `events.html` and `insights.html` got
`site-footer--flush` to close the white band above the footer. Removed the
hub subnav and its CSS from `the-southern-cut.html`. `provenance.html`
rebuilt to match Sourcing/Trading (split hero, three spec-rows, CTA band
"Discuss provenance records." and meta description written, both with the
user's permission; images reused from other pages). Provenance added to
the Services dropdown and footer on all 24 pages, plus a fourth homepage
service card (copy verbatim from the page, `diamond-mine.jpeg`, olive
ground; intro now "Four ways"). Merged `feature/minimal` into `main` and
pushed (`980eb0c`).
**State:** review.py zero errors on provenance and index; 390px seen on
the homepage services only. **Open [CONFIRM]s:** Gaborone address, Cal.com
link, newsletter handler, explainers button, plus the 16 from 2026-10-08.
Stray `southern-cut.html` still there. **Next:** resolve the open
[CONFIRM]s, then run `docs/QA.md` on all pages.

## 2026-10-08 — Homepage ticker, country filter, six explainers

Ticker added to `index.html` (items in markup with own dates; JS computes
status, drops ended). Hub pills now "All countries"; four international
rows added from organiser pages (empty `data-region`, All countries only).
Ended rows sort to the bottom. `.intel` got `.whats-on`'s white panel; all
link arrows removed; explainer cards got hover, pointer and a stretched
link. Six explainer pages built from copy the user approved (Claude drafted
it at their request, an exception to the copy rule), now in `CONTENT.md`.
**State:** review.py zero errors on all pages; 390px checked on the
homepage only. **Open [CONFIRM]s:** 16 in the explainer pages (Sights/year,
De Beers rules, KP wording, ODC/De Beers split, cutting centres, Lejwana
role wording). Stray untracked `southern-cut.html`.
**Next:** resolve the explainer [CONFIRM]s before launch.

## 2026-09-25 (2) — Reveal toggles on the hub, explainers/brief recoloured

Added Read more/less to `.whats-on` (shows 7 of 18 rows) and Show more/
fewer to `.intel` (shows 4 of 6 stories) — both `.button--ghost`, `hidden`
until JS confirms there's more to reveal, positional limit independent of
the existing type/region/category filters. Removed the white gap before
the footer (`the-southern-cut.html` was missing `site-footer--flush`) and
deleted the static "Browse the intelligence" section + its now-dead
`.topics` CSS. Recoloured the page's close: `.explainer-band` (new) is a
dark sapphire>mesh-rise>sapphire gradient with the hero's lattice; white
cards lifted off it (`.engagement__item`'s shadow/radius); `.brief-band`
flipped to light `--colour-sky` + ink lattice, so the page reads dark >
light > dark into the flush footer. **State:** committed and pushed
(`319798b`, `9e82eec` — the latter a masthead standfirst trim made
outside this session, "— each one links straight to its primary source"
dropped). Verified via review.py at 1440/390, zero console errors.
**Open:** 3 pre-existing `[CONFIRM]`s (explainers button, newsletter
handler, Gaborone address) — none new.
**Next:** none flagged — pick up from CONFIRMs or new instructions.

## 2026-09-25 — The Southern Cut rebuilt to match a client-supplied prototype

Rebuilt the-southern-cut.html against `southern-cut.html`, a bundled artifact
the client dropped in root (6 preview boards; "The Southern Cut" board was the
target). New: auto-scrolling `.ticker` (client-directed exception to the
no-looping rule), sticky `.hub-subnav` (jumps + region filter), unified
`.whats-on` calendar (all 18 CONTENT.md dates, live Ended/Live/Today/In-N-days
status computed from real dates, type + region filters), filterable `.intel`
list (real per-article categories, not the mockup's fabricated taxonomy),
numbered `.explainer-grid`, dark split `.brief-band`. Dropped: "Now in the
trade" cards, the old 3-day full table, "The region" country-tab descriptions
(client call). `.insight-card/-grid/-featured` untouched — still used on Home
and article related-reading. CONTENT.md hero copy shortened (client-directed,
overrides CLAUDE.md's normal no-copy-invention rule this once — old text kept
in place, marked superseded). DESIGN.md updated first, per workflow.
**Problems hit, both fixed:** grid/flex children needed explicit `min-width:
0` (table forced 722px→390 viewport overflow); ticker cloned `.trade-status`
verbatim onto the sapphire ground, failing AA at 2.16–2.21:1 — added
`.ticker`-scoped on-dark overrides, contrast.py now clean.
**State:** verified via review.py + contrast.py at 1440/390, zero errors,
all pairings pass. Not yet committed. `southern-cut.html` (3.5MB reference)
and `images/network-get.jpeg` still untracked in root — left alone.
**Open:** 4 pre-existing `[CONFIRM]`s (archive pages, explainers button,
newsletter handler, Gaborone address) — none new this session.
**Next:** review in a real browser, then commit if approved.

## 2026-09-24 — Provenance rule removed sitewide, team/mandate/CTA reworked, forms centred

Removed the provenance rule (hairline+gem, felt repetitive at up to 7×/page)
from all markup and CSS; `docs/DESIGN.md` updated. About: team section is a
2-col grid (`align-items: start` fixes card-stretch on expanded `<details>`),
mandate stages got a card look, CTA band trimmed to heading+button. Contact's
enquiry form and Sourcing's spec-builder (+heading/intro) centred.
**Problem hit:** file changed on disk outside my edits twice — About's
"Beyond a single transaction" eyebrow vanished, and `.mandate__stage` lost
its gem and gained a `.mandate__number` colour using undefined
`--colour--sapphire` (typo, should be `--colour-sapphire`). Flagged, unresolved.
**State:** committed (`fd45c97`), pushed to `feature/minimal`, merged
(fast-forward) into `dev` and pushed. `images/network-get.jpeg` untracked/unrelated,
left alone — `the-network.html` still points to `network-get.jpg` (no "e").
**Open:** Gaborone-address/Cal.com/newsletter markers; the two anomalies above.
**Next:** confirm with client if the eyebrow removal + colour typo were
intentional; fix the typo if not.

## 2026-09-18 — Home hero rewrite, Lesotho added to map, network illustrations, About trimmed

Rewrote Home hero (shorter headline/body, split highlight). Added Maseru
node + link to the hero SVG network map (now shows all 5 countries).
Swapped capability icons and service-card images to new jpegs; gave the
capabilities band a photographic background. Rebuilt The SADC Diamond
Network section: five country illustrations now swap with the existing
`.country-tabs`, wired in `js/main.js`. Trimmed "Where We Sit" copy. On
About: removed the "team behind a mandate" table, swapped the approach
media image, added MBA to Thoko's postnominals. CSS: new
`--colour-off-white` token, border-radius on buttons/cards, new
`.network__intro`/`.network__map` styles + mobile stack.

**Problem hit:** `.cta-band::before` (css/main.css ~L2100-2134) is
currently disabled — a comment above it lost its closing `*/`, so the
gold-gem divider rule got swallowed into the comment. Needs a one-line fix.
**State:** uncommitted on `feature/minimal` (about.html, css/main.css,
index.html, js/main.js + new images/ untracked). Not yet run through
`scripts/review.py` this session — do that before calling it done.
**Open:** same Gaborone-address/Cal.com/newsletter markers as last entry.
**Next:** fix the `.cta-band::before` comment bug, then run
`scripts/review.py` at 1440/390px and confirm no console errors.

## 2026-09-17 — Insight renamed to The Southern Cut; About, home, Advisory, Network rebuilt

Renamed insight.html/insight/ to the-southern-cut.html/the-southern-cut/ sitewide
(links, `_redirects`), rebuilt as a trade-intel hub (masthead, signals, a
collapsible verified-dates calendar, articles, region desk, explainers,
newsletter). Rebuilt About (mandate steps, collapsible bios) and Home
(new capability copy, Services reordered, four-country band replaced by a
five-country SADC section). Built reusable `.country-tabs` (ARIA/keyboard),
used on Home, the hub, and Advisory's `.engagement` disciplines block.
Rewrote The Network as "The SADC Diamond Network" — added Lesotho as 5th
country. Fixed footer/hero-highlight contrast, mobile CTA overflow, a
Privacy heading skip.

**State:** all 16 pages pass review.py + contrast.py. Pushed to `dev` (`f135590`).
**Open:** 16 Gaborone-address markers, Cal.com link, newsletter handler, hub's
unbuilt sub-pages. Network map SVG still shows only 4 countries, no Lesotho.
**Next:** add Lesotho to the network map SVG, or scope that out explicitly.

## 2026-08-21 — Vector logo sitewide, director portraits, real events calendar

**Vector logo shipped sitewide.** The CSS wordmark lockup ("until the vector
lands") replaced by the real SVGs: `images/lejwana-logo.svg` in every header,
and the user-supplied `lejwana-logo-light.svg` (white letterforms) in every
footer — 16 pages, `../images/` prefix on insight pages. Images carry
`alt="" aria-hidden="true"`; the link's `aria-label` names the brand. Dead
lockup rules deleted (`.wordmark__text`, `.wordmark__j`, `.wordmark__j::before`,
`.site-footer__wordmark .wordmark__text`) along with the `--tracking-wordmark`
token. Token comment updated: the vector confirms `--colour-navy` exactly; its
gem is `#80AAD7`, slightly deeper than `--colour-ice` `#A8CCE8`, which other
components share, so the token stays as sampled.

**Director portraits on About.** Four supplied headshots (`thoko-t.jpg`,
`nelly-m.jpg`, `paresh-k.jpg`, `baone-s.jpg`, all 138×138) added to the team
band. Each `.team-director` is now a two-column grid — portrait left,
name/role/bio right — no wrapper div. Circular treatment matches the home rail's
`.rail__image`; rendered at 5.5rem, below natural size, so they stay crisp on
high-density screens. Fixed a pre-existing validation bug in the same block:
Thoko's postnominals `<span>` had no closing tag.

**Imagery debt closed.** The user-supplied stage/network images committed
separately — including `images/RoughTrading.jpg`, which resolves the
`ERR_FILE_NOT_FOUND` carried in the 08-19 entry.

**Events band rebuilt with real events.** The fabricated "Southern African
Polished Forum 2026" removed from the band and replaced with the user's event
table, then trimmed to the four upcoming ones on request (the two completed —
WFDB International Summit, Future of Mining Summit 2026 — dropped, taking the
`.events-band__status` styles with them). Band relabelled "Upcoming events";
rows are hairline-separated list items with title, `<time>`, location and a
one-line relevance note. Titles link out, each URL verified by web search
against the official site before wiring: acmsummit.com,
theopenbusinessgroup.com/slope-stability-x26-home/,
internationalcommoditysummit.com, miningindaba.com — all `target="_blank"`
with `rel="noopener"`. The research-note citation fragments
(`secure.northernminer`, `miningweekly`, `gjepc`…) were left out of the copy.

**Fake forum article fully removed.** Its insight-grid card deleted and
`insight/southern-african-polished-forum.html` git-rm'd — no references remain
anywhere. With the Events category empty, its filter button went too, and
"From the Bourse" (already empty before this session) with it. Every remaining
filter returns at least one article.

**Credentials strip full-width.** User wanted one line. The band's background
was already full-bleed; the content sat in the 1140px container. Container div
removed, `padding-inline: var(--space-3)` instead of `--bleed-inset` — the five
credentials overrun the site container's width (~1167px content vs 1092px
inside insets), so container alignment could never hold one line. Items
distributed `space-between`; column gap tightened to `--space-3` because at
`--space-4` the row overran by a few pixels and orphaned one item. Single line
holds down to ~1312px viewport; wraps to a spread two lines below that, stacks
on mobile as before.

**Copy defect noticed, not touched:** Nelly M's bio begins "focu on the diamond
business…" — typo plus lowercase start. Verbatim-copy rule applies; needs the
client's corrected text.

**Verified:** ad-hoc Playwright checks at 1440px and 390px throughout — logos
render at natural 136×32, portraits load, four events with correct hosts, no
horizontal overflow, zero console errors. Note: `scripts/review.py` does not
currently exist in the repo despite CLAUDE.md referencing it, so verification
used equivalent inline scripts (screenshots captured to temp, console +
pageerror listeners, full-page loads). Cannot visually inspect screenshots this
session — model has no image input — so all checks were programmatic.

**Commits:** `5de3b65` (logo + portraits), `7535129` (imagery), `5052c16`
(events calendar + forum removal), `b440019` (credentials strip). All pushed
to `dev`.

**Next:**
1. Corrected copy for Nelly M's bio from the client.
2. Decide whether credentials may wrap on 701–1311px viewports or need a
   smaller type size to hold one line there.
3. Still open from earlier sessions: Gaborone street address `[CONFIRM]`,
   enquiry form handler, WCAG AA contrast debt, CONTENT.md backfill.

## 2026-08-19 — Rail expanded, Dealing→Trading rename, Lesotho removed

**Value-chain rail: 6 → 8 stages.** Added "Rough Trading" as stage 3 (ODC &
regional state diamond traders), between Rough Sorting and Cutting & Polishing.
Updated intro copy to "Eight stages". CSS grid extended with `nth-child(7)` and
`nth-child(8)` positions; mobile breakpoint already resets all nth-child rules
so no further changes needed there. New image `images/RoughTrading.jpg` not yet
supplied — `scripts/review.py` reports `ERR_FILE_NOT_FOUND` for it.

**Dealing → Trading, sitewide.** Renamed `dealing.html` → `trading.html` and
`images/Dealing.jpg` → `images/Trading.jpg`. Updated nav links, footer links,
service card, page title, eyebrow, form dropdown value, and all 7 insight
article pages — 14 files total. Zero remaining references to "dealing" in HTML
or CSS.

**Country tiles cleaned.** Removed all `country-tile__role` spans from
`index.html` and the associated CSS rules (base + gateway variant). Updated
country copy for Angola, South Africa and Namibia to the new two-line
descriptions provided by the user.

**Lesotho removed from network.** Removed SVG path, map node, link, table row,
and `<details>` country panel from both `the-network.html` and `index.html`.
Updated both `<desc>` elements to drop the Lesotho reference. South Africa's
SVG path retains the Lesotho cutout subpath (geographically correct — the
border still exists), but no Lesotho-specific markup remains.

**Verified:** `scripts/review.py` clean — only `ERR_FILE_NOT_FOUND` for
`images/RoughTrading.jpg` and `images/Trading.jpg` (images not yet supplied).
No layout or JS errors.

**Commits:** `e70e9be` (rail), `f24e6d2` (rename), `fc2d918` (tiles +
Lesotho). All pushed to `dev`.

**Next:** supply `images/RoughTrading.jpg` and `images/Trading.jpg`.

## 2026-08-14 — Events strand shipped, all three branches level

**Shipped the visual pass.** Committed the working tree as `9435db1`, fast-forwarded
`main`, then `dev` — which had been sitting back on `fbcc8b3`, so it took all nine
commits plus the image assets and JS in one go. `main`, `dev` and
`feature/visual-pass` are all level and in sync with origin; tree clean.

**Events strand on Insight.** Sky events band above the article grid (same ink
lattice as the other light bands), an Events filter button, a card in the grid, and
a first event post using a new `.event-details` block. Also `container--wide` on
every header/footer, and the About hero lead fixed — `--bleed-inset` resolves
against the containing block, so a 61% grid *column* started the copy left of the
title; it's a full-width item at `width: 61%` now. Values rows moved to paper ground
with sky rules.

**Live and invented:** `insight/southern-african-polished-forum.html` is a fabricated
event — conference name, 14–16 Oct 2026 dates, Gaborone venue, "attending all three
days". Flagged and pushed anyway on the user's explicit call. The `[SAMPLE:]` marker
is an HTML comment, so visitors see it as genuine. Linked from the band, the grid and
the filter. **Open `[CONFIRM:]`:** full Gaborone street address, in every page footer.

**Verified:** `scripts/review.py` clean — zero console/page errors at 1440 and 390px.

**Next:** replace the sample event with a real one, or pull the page and its three
Insight links before the site goes live.

## 2026-08-12 (later) — Value-chain rail and Services rebuilt to comps

**Rail rebuilt to `images/sky.png`.** Six-column strip → staggered zig-zag: cards
alternate either side of a gold spine, each half a step below the last (grid rows
1–6, `span 2`), connector line plus a botswana diamond node per card. White pills
rounded on the image side, 118px circular stage photo pinned absolute so a long
detail line wraps under the name; polished stage inverts to sapphire. Stage
numbers are a CSS counter — the `<ol>` already carries the sequence, and
`display: grid` on the `li` kills `list-item`, so `counter(list-item)` was out.
Collapses to one column at 900px with spine and connectors dropped.

**Services rebuilt to `images/services.png`** — three full-width rows, image and
tinted ground swapping sides. Two gotchas: the image only filled the row once
`height: 100%` became `align-self: stretch` + negative block margins; on mobile
the base `img { max-width: 100% }` clamped the bleed until `max-width: none`.

**Three new tokens.** The comps' tints are off-palette, so `--ground-olive`
`#EEF3E0` and `--ground-gold` `#FBF2E4` are derived from signature and mark with
`--colour-sky` as the third, plus `--colour-gold-line` `#CDB78E` sampled from
`sky.png` for hairlines and the spine. Client may still want the comp hexes.

**Copy gap, not marked in the HTML:** `sky.png` shows a *second* detail line on
every stage ("Extraction from earth's deepest sources", "Our focus" on polished,
etc.) that CONTENT.md does not have. Rendered name + existing detail only rather
than invent six lines; no `[CONFIRM: …]` inserted, so it lives here. `services.png`
labels all three links "About brokerage" — kept the correct per-service links.

**Verified:** zero console/page errors at 1440, 1024, 950 and 390px, no
horizontal overflow. Still uncommitted, `images/` still untracked — the nine new
JPEGs are load-bearing, `services.png`/`sky.png` are comps.

**Next:** get the six stage detail lines from the client, then the capabilities
background image.

## 2026-08-12 — Dark hero confirmed, mesh ground, hero widened, capability cards

**Dark hero chosen**, and it cost no markup: `index.html` already carried
`.hero--dark`. Deleted the unused `.hero--light` rule instead, which also closes
the `--on-dark` button problem — those buttons only vanished on the light ground.

**Mesh ground.** `images/mesh.png` sampled on a 9×5 grid turns out to be a
horizontal ramp, not a mesh: sapphire to a crest at 75%, deepening at the edge.
Rebuilt as one `linear-gradient` on three ground tints rather than shipping the
raster, which would stretch as the hero's height changes; lattice is the supplied
`diamonds.svg` on `::before` at 10%. Matches the comp exactly at the 50% and 75%
stops. DESIGN.md §7a. **Measuring it caught two contrast problems**: map outlines
fell to 2.35:1 on the crest (raised to 70% white → 3.16:1, Maseru with them), and
my first mobile ramp put the gold eyebrow at 2.67:1, worse than the 3.72:1
already on the books — ending the ramp at 38% returns it to flat sapphire. The
mesh adds no new debt. §13.

**Hero widened** via `.container--wide`; the headline drops to three lines. The
header is still capped at 1140px, so the wordmark is inset from the hero copy.

**Capabilities rebuilt to `capabilities.png`** — white cards, label → gem →
supplied icon → benefit. Three deltas were put to the client, not assumed: the
paragraph goes (confirmed), the sand ground does not (a background image is
coming instead — **not supplied yet**, cards are on white), and the comp's gold
benefit rule loses to olive, the approved signature colour. §7b.

**Cutting the paragraphs removed every named fact from the band** — GIA,
Kimberley Process, iTraceiT, London Diamond Bourse, certified polisher. They
survive on the service pages and About, but the home page now states none. Text
kept verbatim in CONTENT.md; the client said "too wordy", not "remove it".

**Verified:** `index` and `the-network` zero console/page errors, 1440px and
390px, every ratio measured from rendered pixels rather than estimated.

**Uncommitted, and `images/` is untracked.** Four files are now load-bearing
(`diamonds.svg` + the three icons, ~40KB). The other three are comps —
`mesh.png` alone is 677KB — and nothing ignores them, so a blind `git add`
publishes them to the public repo. Decide before committing.

**Next:** the capabilities background image, then client sign-off on the
authored copy (CONTENT.md), the WCAG AA contrast debt, and the logo vector.

## 2026-08-11 — Visual pass on client feedback (branch `feature/visual-pass`)

Client verdict on the live draft: structure and text approved, one section too
wordy, needs visual moments especially on The Network, plus a hero map of
Southern Africa with cities as blue diamonds. Real logo supplied. Built on a
branch; `main` and `dev` keep the published build.

**The design spec surfaced mid-session.** `~/Desktop/lejwana/lejwana_design_spec.pdf`
turned out to be the approved spec, and the build diverges from it in seven
places — type (Lora/Work Sans vs Cormorant/Inter), signature device, hero
structure, credentials strip, service numerals, benefit-line treatment, palette
count. All now recorded in DESIGN.md §13 as decisions rather than drift. Kept
Lora + Work Sans because the client approved the live pages as typeset.

**The two supplied images resolved what the text left vague.** "Too wordy" was
the *home capabilities band*, not The Network — I had assumed wrongly. And the
logo is a Garamond-family serif with a light-blue diamond above the J, where the
build had wide-tracked Work Sans with a *gold* square. So the gem moved from
gold to ice sitewide and gold narrowed to a label-only role.

**Built:**
- Network map — bespoke inline SVG, geometry generated from public per-country
  GeoJSON, pasted as static paths. Hierarchy encodes the argument: Gaborone
  largest and full weight, three producers medium, Maseru half-scale at 55%
  opacity. In the hero and on The Network.
- The Network rebuilt: five stacked prose blocks became map + comparison table +
  `<details>` panels. The five country paragraphs moved verbatim.
- Capabilities band condensed (~50 → ~28 words each), gem anchors, flex columns
  so the pillars end level, olive left rules.
- Value-chain rail from the spec, homepage, kept typographic.
- Gateway treatment on the Botswana tile, matching the map.
- Wordmark rebuilt to match the real logo; Cormorant Garamond loaded subsetted
  to the seven glyphs of LEJWANA.
- Scroll reveal + map draw-in, both reduced-motion safe.

**Two bugs found and fixed, both real:**
1. The first reveal used `IntersectionObserver`. A fast scroll can carry an
   element from below the viewport to above it between two intersection
   computations, so it never reports as intersecting and the section stays
   invisible **permanently**. Reproduced: three of five sections lost. Replaced
   with a scroll-position check that cannot skip.
2. `scripts/review.py` scrolled with `scrollTo` while `html` has
   `scroll-behavior: smooth`, so its scroll pass never reached the bottom and
   lower sections were never triggered — it had been silently under-testing
   every page. Now forces instant scrolling for the pass.

**Stock imagery rejected.** Unsplash was trialled as instructed. `source.unsplash.com`
is dead (503) and the search API needs a key (401), so only blind photo-ID
fetches work. Five retrieved, **zero usable** — all consumer jewellery, rings on
hands, or unrelated. That is the exact register the rebuild exists to escape, so
nothing shipped.

**Accessibility debt measured, not estimated.** Three pairings fail WCAG AA and
all predate this pass: gold on white 3.09:1, gold on sapphire 3.72:1, Botswana
Blue on white 2.46:1 (which also fails the 3:1 focus-outline minimum). Recorded
in DESIGN.md §13 and QA.md. The gateway tile sidesteps one by using ice at
6.83:1; the rest need a darker gold for text.

**Verified:** 14/14 pages zero console errors; 1440px and 390px; no page-level
horizontal overflow; reduced-motion leaves nothing hidden; panels open and the
map renders with JS disabled; one h1 per page, no heading skips.

**Next:**
1. Choose the hero ground — both built, screenshots compared. Dark is stronger;
   the light variant still needs its buttons fixed (it inherits `--on-dark`
   variants that vanish on a light ground).
2. Client sign-off on the authored copy — every changed line is in CONTENT.md.
3. Fix the contrast debt, or accept it in writing.
4. Logo vector, to replace the CSS lockup and re-sample navy and ice.

## 2026-08-11 — Docs cleaned, build promoted to root, site published

Housekeeping session. No design or copy changes.

**Structure.** The full build had been sitting in `website/` while the repo root
still held the empty scaffold (`index.html`, `css/main.css`, `js/main.js`) that
CLAUDE.md named as the working file — two competing `index.html`s. Promoted
`website/` to the repo root and deleted the scaffold. Relative paths meant the
directory structure was unchanged by the move, so nothing needed rewriting.
Re-verified after: `index`, `contact`, `insight` and `insight/on-yield` all shoot
clean at 1440px and 390px with zero console/page errors.

**Docs.** Two files had as-built content appended below their untouched scaffold
templates rather than replacing them:

- `DESIGN.md` — dropped the placeholder template that sat above the real system.
  Added §0 (what's built, file map, path convention) and §12 (the three
  divergences from CLAUDE.md's code standards, with a status on each).
- `SESSION_LOG.md` — the whole of `website/README.md` had been pasted in below
  the scaffold entry. Removed.

`website/README.md` itself was redundant with the docs — its three sections were
distributed and the file deleted: structure → `DESIGN.md` §0, design decisions →
already in `DESIGN.md` §1–8, open items → `LAUNCH.md`. One home per fact.

- `LAUNCH.md` — was a six-line stub. Now carries the nine launch blockers, the
  open client questions and the deploy sequence.
- `QA.md` — checklist kept as-is; added a verification-status table separating
  what has actually been checked from what has not.
- `CONTENT.md` — status corrected. Copy is final but lives only in the markup;
  flagged as an open item.

**Verified.** DESIGN.md's claims were checked against `css/main.css` before
being kept — palette, type scale, spacing tokens, the two breakpoints, the four
documented off-palette hexes and the no-animation rule all hold.

**Published.** First push. Added `origin` →
`github.com/buildwithmbo/lejwana.git` (public, previously empty). Commit
`fbcc8b3`, 17 files: the 14 pages, `css/main.css`, `js/main.js`, `.gitignore`.
Pushed `main` and `dev`; fast-forwarded `main` to `dev` first so the default
branch carries the site rather than the empty scaffold.

Two things happened during the push worth remembering:

- **Commits were re-authored.** GitHub rejected the first push: both commits
  were authored as `lolothedesigner@gmail.com` and the account has
  email-privacy protection on. Re-authored both to
  `13999450+buildwithmbo@users.noreply.github.com` and set that as this repo's
  **local** git identity (`git config --local`), so commits here stay private
  without touching the global config. File trees verified byte-identical
  before and after. Side effects: commit SHAs changed (nothing had been pushed,
  so harmless) and `--reset-author` reset the author timestamps to the push
  time — both commits were from today anyway.
- **The docs are not in the repo.** `.gitignore` excludes `docs/`, `CLAUDE.md`,
  `.claude/`, `scripts/`, `screenshots/` and all `*.md`, so everything cleaned
  this session exists only on this machine. Deliberate — internal planning
  never reaches the public repo — but it means DESIGN.md, LAUNCH.md and this
  log have **no backup and no history**. Worth a private repo or a docs branch.

Because the repo is public, the placeholder content is now publicly readable —
the `[CONFIRM: full Gaborone street address]` markers and the `lejwana/intro`
Cal.com stub. Harmless in a repo, but it would go live as-is if GitHub Pages is
enabled, and the enquiry form still has no handler.

**Next:** four things, in order of cost of getting them wrong —
1. The enquiry form has no handler. It validates and shows a stub success state,
   so it currently loses every lead. `LAUNCH.md` blocker 3.
2. Decide how the docs get backed up, or accept that they are machine-local
   only. One disk failure loses the whole design system of record.
3. Resolve the CSS section-naming divergence: renumber `main.css` to CLAUDE.md's
   `|| SECTION` convention, or amend CLAUDE.md. Both are documented right now,
   which is the worst of the two options.
4. Backfill `CONTENT.md` so the copy has a source of truth.

## 2026-08-11 — Scaffolded
Project created with blkswan-new. Nothing built yet.
Next: fill in CLAUDE.md "What this is", then /start design.
