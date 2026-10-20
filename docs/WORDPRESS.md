# WORDPRESS.md — Lejwana on WordPress

Plan for moving the whole site to WordPress. **Status: agreed 2026-10-20, not built.**
Revised the same day: everything is WordPress, no static/WP hybrid.

## 1. Scope and decisions

| Decision | Choice |
|---|---|
| What moves | The whole site: 9 pages, The Southern Cut hub, What's on, Insights, six explainers. All at once, one cut-over. |
| What the client edits | Events and articles. Nothing else. |
| Other pages | Theme templates, edited by the developer |
| Theme type | Custom **classic** PHP theme, no page builder |
| Fields | Native WordPress meta, **no ACF** |
| Hosting | **SiteGround GoGeek** (includes staging). WordPress at the root of `lejwana.com`. |
| Events | One events list drives the What's on table and the homepage ticker |
| Articles | Standard posts in the block editor |
| Contact form | Small custom handler in our plugin, emails Lejwana |
| Logins | Client users added manually. Start with one Editor account. |
| Related articles | Automatic, two posts from the same category |

## 2. Approach

**Classic theme, not a block theme.** A classic theme is PHP template files
([template hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/)).
The existing hand-written BEM and semantic HTML moves into the templates
almost unchanged, so CLAUDE.md's markup standards carry over. The Site Editor
stays hidden, so the client cannot break the layout.

**Plugin plus theme.** WordPress
[recommends custom post types live in a plugin](https://developer.wordpress.org/plugins/post-types/registering-custom-post-types/),
so the client's data survives a redesign.

- Plugin `lejwana-core`: Event post type, event fields and meta box, country
  taxonomy, contact form handler.
- Theme `lejwana`: presentation only.

**Native fields, not ACF.** ACF was forked into Secure Custom Fields in
October 2024
([WP Tavern](https://wptavern.com/acf-plugin-forked-to-secure-custom-fields-plugin)),
which makes it a poor long-term dependency. Core `register_post_meta` plus one
PHP meta box needs no build step and no extra plugin.

## 3. Data model

| Thing | WordPress | Notes |
|---|---|---|
| Event | Custom post type `lj_event`; title is the item name | Not public, so no single pages. Each row links out, as today. Block editor off, so the editor is a plain form. |
| Event fields | Start date, end date (optional), type (Sale, Tender, Result, Event), link URL, place, status note | Upcoming, live or ended stays computed in `js/main.js`. |
| Event countries | Taxonomy `lj_country`: Botswana, Angola, South Africa, Namibia, Lesotho | Maps to today's `data-region`. No country means "All countries" only. |
| Insight or explainer | Standard `post` | Categories match today's labels. Meta line is "Lejwana · {post date}". |
| Related articles | Two posts from the same category | A category with one post falls back to the latest posts from other categories. |
| Contact enquiry | Emailed with `wp_mail` | See section 5. |

The events loop outputs the current `<tr data-type data-region data-start
data-end>` markup, so filtering, sorting and status in `js/main.js` work
unchanged. Pages are server-rendered and work without JS. Status stays
client-side so a cached page never shows a stale "Live".

## 4. Theme templates

- `header.php` and `footer.php`: nav and footer in one place, copied verbatim
  from the static site.
- One template per page: `front-page.php`, About, Sourcing, Trading,
  Provenance, Advisory, The Network, Contact, Privacy. Copy is the existing
  markup, verbatim from the static pages and `docs/CONTENT.md`.
- Southern Cut: hub template, `archive-lj_event.php` (What's on), `home.php`
  (Insights), `single.php` (article).
- `index.php` (fallback) and `404.php`.
- The homepage ticker is rendered by the template from the next ~8 upcoming
  events, so there is no REST route.
- Shared `css/main.css` and `js/main.js`, used as is.

Editor controls: the block editor allows paragraph, heading (h2 and h3), list
and quote only. An editor stylesheet makes the editing view match the front
end.

Open `[CONFIRM]`s carry over into the templates unchanged (Gaborone address,
Cal.com link, newsletter handler, explainers button, explainer fact-checks).

## 5. Contact form

The handler lives in `lejwana-core`. It validates server-side, checks a nonce
and a honeypot field, and sends the enquiry to Lejwana with `wp_mail`. The
existing front-end validation and microcopy stay. Mail delivery uses
SiteGround's mail setup; test that messages land and do not go to spam.
Enquiries go to `info@lejwana.com`.

## 6. Hosting and URLs

- Build and test on a GoGeek staging copy. At cut-over, WordPress goes live at
  the root of `lejwana.com` and the static files are retired.
- Permalinks: `/about/`, `/sourcing/`, and so on; `/the-southern-cut/` hub,
  `/the-southern-cut/events/`, `/the-southern-cut/insights/`,
  `/the-southern-cut/{slug}/` for articles.
- Every old `.html` URL gets a 301 to its new URL (a redirect map in
  `.htaccess`, checked in phase 7).
- Canonical, og:url and sitemap use the `lejwana.com` URLs.

## 7. Speed

- Enqueue only `main.css`, `main.js` and the existing font links. No jQuery.
- Dequeue emoji, embeds and `wp-block-library` CSS on the front end.
- Images: sizes matching current dimensions, native lazy loading. Hero images
  stay eager.
- Page caching from SiteGround's server-level caching (Site Tools, Speed).
  Keep SiteGround's Speed Optimizer and Security Optimizer plugins; add no
  other caching plugin.
- No SEO plugin. The theme outputs title, description and OG tags; core
  provides sitemaps.
- Set PHP to 8.4 or later in Site Tools
  ([hosting handbook](https://make.wordpress.org/hosting/handbook/server-environment/)).
- Target: Lighthouse performance and accessibility ≥ 90 on mobile.
- Rule: no further plugins without approval.

## 8. Phases

| # | Phase | Output | Done when |
|---|---|---|---|
| 0 | This document agreed | Approved plan | Signed off 2026-10-20 |
| 1 | Local setup: LocalWP, `wp/theme/` and `wp/plugin/` in this repo. Fresh WordPress install and staging copy on SiteGround. | Empty theme and plugin activate locally and on staging | Clean install, no PHP notices |
| 2 | Plugin: Event type, fields, taxonomy, meta box, contact handler | Client can add an event | Event saved; test enquiry arrives by email |
| 3 | Theme core: header, footer, front page and the 8 other page templates | Every static page reproduced | `scripts/review.py` match at 1440 and 390 |
| 4 | Theme Southern Cut: hub, What's on, Insights, article, 404, editor styles | Every Southern Cut page | Filters work, zero console errors, heading order, vnu clean |
| 5 | Migration: WP-CLI script turns the existing table rows into events; paste the six articles in verbatim | Content in WP | Copy matches `docs/CONTENT.md` |
| 6 | Homepage ticker rendered from events | Ticker driven by events | Hidden when no upcoming events |
| 7 | QA: Lighthouse, keyboard, vnu, redirect map, reduced motion | `docs/QA.md` updated | ≥ 90 mobile, all checks green |
| 8 | Handover: staging to production, DNS, Editor login, one-page "add an event or article" guide, auto-updates and host backups confirmed | Live | Client adds a test event unaided |

## 9. Risks

- **Copy review.** Content edited in WordPress bypasses the `[CONFIRM]` and
  verbatim-copy checks. The client reviews copy before it is published.
- **Cut-over.** URLs change. The redirect map needs a dry run on staging. The
  domain is already at SiteGround, so there is no DNS transfer, but check the
  existing mail records are untouched.
- **Mail delivery.** Contact enquiries are the conversion goal. Test delivery
  before launch.
- **Plugin sprawl** is the usual way WordPress sites get slow (section 7).

## 10. Decisions (answered 2026-10-20)

1. **Static site on SiteGround?** No. WordPress is built on a fresh SiteGround
   install and the static site is retired at cut-over, so no interim move.
2. **Plan:** GoGeek, which includes staging.
3. **Hub fixed copy** is developer-edited. Agreed.
4. **Related articles:** automatic by category.
5. **Logins:** added manually when needed.
6. **Whole site in WordPress**, all at once; not a subdomain, not a hybrid.
7. **Non-Southern-Cut pages** are developer-edited templates.
8. **Contact form:** small custom handler, sending to `info@lejwana.com`.
9. **Domain** is registered at SiteGround.
10. **Copy review:** the client reviews copy before publishing.

## Sources

- SiteGround: [staging](https://eu.siteground.com/kb/access-staging-site/)
- [Template hierarchy](https://developer.wordpress.org/themes/basics/template-hierarchy/)
- [Registering custom post types](https://developer.wordpress.org/plugins/post-types/registering-custom-post-types/)
- [Block editor meta](https://developer.wordpress.org/block-editor/how-to-guides/metabox/)
- [What is a theme](https://developer.wordpress.org/themes/getting-started/what-is-a-theme/)
- [Hosting server environment](https://make.wordpress.org/hosting/handbook/server-environment/)
- [ACF fork, WP Tavern](https://wptavern.com/acf-plugin-forked-to-secure-custom-fields-plugin)
