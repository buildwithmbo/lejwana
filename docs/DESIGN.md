# DESIGN.md — Natural Polished Diamonds · Southern Africa

Single source of truth for the look. `css/main.css` implements this file — it
does not invent it.

The design system **as built**. This is the reference for any future session,
page or Phase Two module. If a new element can't be assembled from what's here,
question the element before extending the system.

**One line:** serious trade house. The information, structure and typography do the work. The network map is the one device that earns its place, because it states an argument rather than decorating one.

---

## 0. What's built

Eight pages plus six launch Insight articles. Vanilla HTML/CSS/JS, BEM
throughout, no dependencies, no build step — the files in the repo root are the
deployable output.

```
index.html                 Home (dark sapphire hero)
brokerage.html             Services — Brokerage
dealing.html               Services — Dealing
advisory.html              Services — Advisory
the-network.html           The SADC Diamond Network (five countries: Botswana,
                           Angola, South Africa, Namibia, Lesotho — the map itself is
                           commented out mid-file, still shows four; see note below)
the-southern-cut.html      The Southern Cut — trade-intelligence hub (ticker, masthead,
                           sticky subnav, filterable "What's on" calendar, filterable
                           "Latest intelligence", explainers, newsletter). Replaced
                           insight.html 2026-09-17; rebuilt again 2026-09-25 to match a
                           client-supplied prototype (docs/SESSION_LOG.md has the detail);
                           _redirects carries the old URLs on Netlify
about.html                 About
contact.html               Contact (email copy, lazy Cal.com, enquiry form)
the-southern-cut/          Six launch articles (was insight/)
css/main.css               Single stylesheet, BEM, labelled sections
js/main.js                 Nav, email copy, form validation, lazy Cal.com, filter
images/                    diamonds.svg + diamonds-ink.svg (lattice, dark and
                           light grounds), local/clearing/advisory.svg
                           (capability icons), small-diamond-pattern.svg
                           (About card corners), twelve JPEGs (six rail
                           stages, three service rows, about-hero, our-team,
                           4-diamonds)
```

Four files in `images/` are **comps, not build assets**, and are gitignored by
name so they never reach the public repo: `mesh.png` (§7a), `capabilities.png`
(§7b), `sky.png` (the rail), `services.png`. `diamond-pattern.svg` is ignored
too — it seams when tiled and is unused (§7a). They are reference only and exist
on the build machine alone; anything load-bearing must be tracked.

Links and asset paths are **relative** (`href="about.html"`,
`href="../css/main.css"` from `the-southern-cut/`), so the site runs from `file://` and
from any subdirectory without a server. See §12 for how this diverges from
CLAUDE.md.

---

## 1. Design principle

From the brief, and binding on everything:

> Serious trade house. Southern African expertise. Provenanced natural polished diamonds. Clear commercial value.

The visual references are Bonas Group and I. Hennig & Co — clean, spare, institutional. If a proposed change would read as jewellery retail, consumer marketing, or decoration for its own sake, it's wrong.

**Never:** sparkle effects, rotating gemstones, consumer jewellery imagery, hands holding diamonds, safari imagery, decorative layouts, looping or decorative animation.

---

## 2. Colour

Defined as CSS custom properties in `css/main.css` → `01. Tokens`.

| Token | Name | Hex | Role — use for this and nothing else |
|---|---|---|---|
| `--colour-alpine` | Alpine White | `#FFFFFF` | Main background |
| `--colour-sapphire` | Sapphire | `#1E3A5F` | Headings, buttons, dark hero, footer, CTA bands, wordmark |
| `--colour-botswana` | Botswana Blue | `#75AADB` | Links, active nav states, focus outlines |
| `--colour-sky` | Sky | `#E8F0F7` | Soft section backgrounds, cards, borders |
| `--colour-olive` | Olive | `#6B7C4F` | Benefit lines, Insight category tags/meta, quote rules, membership line. Nowhere else |
| `--colour-gold` | Gold | `#B08D57` | Eyebrows, capability labels, tile roles. **Label colour only — no longer the gem** |
| `--colour-charcoal` | Charcoal | `#1A1A1A` | Body copy |
| `--colour-muted` | Muted | `#6B6B6B` | Map labels, table meta, captions |
| `--colour-navy` | Logo navy | `#16295A` | Wordmark letterforms only |
| `--colour-ice` | Logo ice | `#A8CCE8` | Wordmark diamond, network map nodes, gateway marks |

### Marks and inks are different colours

Gold, Botswana Blue and Olive are brand marks. As **text** they failed AA —
3.09:1, 2.46:1 and 3.95:1 on the grounds they actually sit on. Each therefore
has a text-weight variant, same hue and saturation, darkened until it clears
4.5:1 on white *and* on `--colour-sky`, plus one lightened for sapphire:

| Token | Hex | Measured | Use for |
|---|---|---|---|
| `--colour-gold-ink` | `#84683E` | 5.21:1 white · 4.52:1 sky | Eyebrows, capability labels, tile roles, table headers, panel roles |
| `--colour-botswana-ink` | `#2D70AE` | 5.20:1 white · 4.51:1 sky | Link underlines and hovers, nav active/hover rules, panel signs, form focus |
| `--colour-olive-ink` | `#627249` | 5.22:1 white · 4.53:1 sky | Benefit lines, Insight categories and meta, membership line |
| `--colour-gold-light` | `#CDB78E` | 5.89:1 on sapphire | Gold text on dark: the hero eyebrow, footer headings. Also the hairline weight (was `--colour-gold-line`) |
| `--colour-ice-light` | `#C7DEF0` | 4.88:1 on the mesh rise · 8.29:1 on sapphire | Ice as type on dark: the hero highlight paragraph. `--colour-ice` itself measures 4.09:1 where the mesh rises under the copy column |

**The rule: the brand hex for gems, rules, borders and backgrounds; the ink for
type and UI.** `--colour-gold`, `--colour-botswana` and `--colour-olive` are
still correct on the provenance rule, the team-card rule, the capability gem,
the map nodes, the quote rule and the active filter ground. None of them is a
text colour.

`--focus-ring` resolves to `--colour-botswana-ink` and is re-declared as
`--colour-ice` on `.hero--dark`, `.cta-band` and `.site-footer`. One ring cannot
pass on both white and sapphire, so the ground redefines the token and
`:focus-visible` stays a single rule.

Navy and ice are **sampled from the supplied logo PNG and are approximate**.
Re-sample from the vector when it arrives.

### The gem is blue, not gold

The supplied logo puts a light-blue diamond above the J. That is the brand's own
mark, so the gem colour moved from gold to ice everywhere it appears, and gold
narrowed to a label-only role. This is why the client's "shiny blue diamonds" on
the map rhyme with the wordmark instead of competing with it.

### Rules

- **Maximum three palette colours per screen** (charcoal body text doesn't count against this; it's the text default). Sapphire is always the primary.
- Olive is the Insight/benefit signature. It never appears as a general accent, button, or background.
- One derived value exists: `#a8b88a`, olive lifted for contrast on the sapphire footer (`.site-footer__membership`). The four ink variants above are the other contrast-driven derivations. Don't add more without a contrast reason.
- The ink variants are the same hue as their marks, so they don't count as extra colours against the three-per-screen rule.
- Hover darken for sapphire buttons: `#16304f`. Form borders: `#c6d4e2`. Error red: `#9b3535`. These are the only off-palette values in the file.
- Three ground tints — `--mesh-rise` `#285079`, `--mesh-crest` `#3B79AA`, `--mesh-edge` `#226796` — sampled from `images/mesh.png` for the home hero (§7a). Ground only, never ink, and confined to `.hero--dark`, so they don't count against the three-colours rule.

---

## 3. Typography

Loaded from Google Fonts with `display=swap` and preconnect.

| Role | Face | Weight | Where |
|---|---|---|---|
| Headings | **Lora** | 400 / 500 | H1s, section titles, serif secondary lines, quote rules, tile/card names. Sparingly — Lora signals hierarchy, so it must stay scarce |
| Body | **Work Sans** | 300 | All body copy, line-height **1.65** |
| Utility | **Work Sans** | 500, uppercase, tracked | Eyebrows, labels, nav-adjacent UI, wordmark |

### Scale (tokens)

- H1: `clamp(2rem, 4.5vw, 3.25rem)`
- H2: `clamp(1.5rem, 3vw, 2.125rem)`
- H3: `1.25rem` · Body: `1.0625rem` · Small: `0.875rem` · Eyebrow: `0.8125rem`
- Tracking: eyebrows `0.14em`, wordmark `0.28em`

### Wordmark

Matches the supplied logo: wide-tracked uppercase **Cormorant Garamond 500** in
navy, with the ice diamond above the J. Classes: `.wordmark`,
`.wordmark__text`, `.wordmark__j` (carries the diamond as `::before`). Footer
variant flips the letterforms to white.

Cormorant Garamond is loaded **subsetted to the seven glyphs of LEJWANA**
(`&text=LEJWANA`), so it costs almost nothing and cannot leak into page type.
**It is not part of the type system** — Lora remains the heading face.

Still a CSS lockup, not the real asset. Replace with the vector when supplied.

---

## 4. Signature devices

### The network map — the star

A bespoke inline SVG of Southern Africa, in the home hero. Not decoration: it
states the argument the copy makes. Botswana is the gateway (largest node,
full weight), four countries produce, and Lesotho is drawn dimmed and
half-scale because it "sits outside the core network but inside its reach" —
the hierarchy *is* the content.

Geometry is generated from public boundary data by `scripts/`-adjacent workings
and pasted in as **static paths**, so there is no runtime dependency and no geo
library. Colour comes from tokens, so the one component serves both the sapphire
hero and light grounds. Nodes use the logo's diamond in the logo's ice blue.

To regenerate: the projection is equirectangular, fitted to the five countries,
`viewBox="0 0 240 300"`. Country rings come from per-country GeoJSON; the
Lesotho hole in South Africa relies on `fill-rule="evenodd"`.

**The Network page no longer uses this SVG (2026-09-24).** Its
`.network-summary__figure` now holds the client's watercolour illustration,
`images/network-map.jpg`, in place of the inline component — same five nodes
and labels, hand-painted rather than generated. Swapped on request; the SVG
stays live-generated code, not deleted, since the home hero still depends on
it and regenerating this one illustration from scratch isn't the goal. Treat
any future "add Lesotho"/"update a node" request as applying to the hero's
copy only unless told otherwise.

### The value-chain rail

Six stages, mine → finger, with Lejwana's stage ticked olive. From the design
spec. Homepage only, kept purely typographic so it informs without competing
with the map.

### The provenance rule — removed 2026-09-24

A hairline divider with a small gold rotated-square gem, echoing the wordmark, that marked the start of every major section (`<hr class="provenance-rule">`, with `--on-dark` and `--centred` variants).

It was never in the design spec — the spec's signature element is the value-chain rail — and was invented during the first build. Kept for a while because it did useful work marking section starts, but at one instance per section (up to seven on a single page) it read as repeated boilerplate rather than a signature mark, so the client asked for it to be dropped. Markup and CSS removed sitewide; sections now rely on their own spacing and heading hierarchy to mark a new start.

### The gem, systematised — 2026-08-13

The provenance rule's small rotated square already recurred nine times before
this pass — wordmark, capability card, gateway tile, rail node, table row,
open panel, credentials separator — each declared independently, because CSS
has no mixin to share a shape across real elements and `::before`/`::after`
pseudo-elements alike. That's not fixed here: it's the reason the shape gets
extended by hand rather than refactored into a utility class. What changed is
coverage — the mark now appears wherever a section opens, closes, or labels
itself, so nowhere on the site introduces a shape the rest of the system
doesn't already use.

**New instances**, all `::before`, all a small rotated square at
5–9px, none needing a markup change:

| Element | Colour | Reads as |
|---|---|---|
| `.site-footer__heading` | `--colour-gold-light` | Opens "Services" / "Contact" on every page's footer |
| `.cta-band` | `--colour-gold-light` | Closes every sub-page, centred above the title — the provenance rule's mirror: it opens a section, this ends one |
| `.article__category`, `.insight-card__category` | `--colour-olive-ink` | Insight had no gem anywhere before this pass |
| `.insight-featured__category` | `--colour-ice` | Hub-only, dark ground |
| `.proposition__point-title` | `--colour-botswana-ink` | Service-page proposition points |
| `.prose-section__list li` | `--colour-gold-ink` | Was the browser default disc — the one plain list on the site |

**Colour follows the existing logic**, not a new rule: gold marks a section
boundary (provenance rule, now the footer and CTA), botswana marks a
data/network point (map, rail, table — now proposition points), ice marks a
dark-ground or logo-adjacent accent (wordmark, gateway tile — now the hub's
dark category chip), olive marks Insight (category tags, benefit lines — now
the gem inside them too).

### The lattice, extended to light grounds — 2026-08-13

`images/diamonds.svg` (§7a) only ever shipped for dark grounds — its stroke is
`#F5F5F5` at 0.5 alpha, which is close enough to `--colour-sky` that even full
opacity barely registers against it. **`images/diamonds-ink.svg`** is the same
two rects at `--colour-sapphire`, for `.section--sky::before` at `opacity: 0.12`
— low enough to sit under the text as texture, not compete with it; 0.4 was
tried first and read as pattern rather than ground. This is now the site's
second-most-visible surface after the hero: every `.section--sky` on every
page (about, contact, advisory, brokerage, the-network, index) carries it, so
the light bands now echo the dark hero and team band's lattice instead of
sitting apart from it.

Same technique as `.hero--dark::before` and `.team-band::before`: `position:
relative; isolation: isolate` on the section, the pattern on its own `::before`
layer so the opacity applies to the pattern and not to the section's children.

---

## 5. Spacing & layout

- Spacing scale: `--space-1` 0.5rem → `--space-6` 6.5rem. Use tokens, not magic numbers.
- **The top three steps are fluid (2026-08-13).** `--space-4` `clamp(1.75rem, 3.5vw, 2.5rem)`, `--space-5` `clamp(2.25rem, 5.5vw, 4rem)`, `--space-6` `clamp(3rem, 8vw, 6.5rem)`. Each holds its original value from roughly 1150px up, so **desktop is unchanged**; the scale only tightens where the viewport can't afford it. `--space-1` to `--space-3` stay fixed — they set type and component rhythm, which shouldn't move with the viewport.
- Site width: `1140px` (`.container`). Reading column: `680px` (`.container--reading`) — used on all service-page prose and article bodies, per the brief's 640–720px spec.
- `.container--wide` uncaps the width for the home hero only (2026-08-12). Gutter is `clamp(--space-3, 4vw, --space-5)`, so it opens up on desktop and falls back to the base container's inset on narrow screens. **The header is still capped at 1140px**, so the wordmark sits inset from the hero copy below it — open question, not an oversight.
- Section rhythm: `.section` = 6.5rem vertical padding on desktop, 3rem at phone widths. Alternate white / `.section--sky` grounds to break long pages without borders.
- Whitespace is the layout system. If a section feels empty, it's correct — **on desktop**. At 390px the fixed scale put 104px above *and* below every section, so two stacked sections opened 208px of empty scroll, over half a phone viewport with nothing in it. Generous margins on a 1440px canvas are not generous on a 390px one; the same token has to mean different amounts of room. Tightening the scale took 12% off the height of the site (index alone lost 1052px).

---

## 6. Components (BEM inventory)

All in `main.css`, one block per labelled section. Naming: `.block__element--modifier`.

| Block | Purpose | Notes |
|---|---|---|
| `.site-header` / `.nav` | Header + primary nav | Services dropdown (`.nav__item--dropdown`), burger below 700px, `.nav__link--active` on current page |
| `.hero` | Page heroes | `--dark` (home: the mesh ground of §7a — sapphire ramping to a crest under the map, diamond lattice on `::before`), `--page` (sub-pages: light, bordered) |
| `.button` | CTAs | `--ghost`, `--on-dark`, `--ghost-on-dark`. Square corners everywhere — no radius on this site |
| `.text-link` | Inline links | Botswana-blue underline, sapphire text |
| `.eyebrow` | Gold tracked labels | Above H1s and section-openers |
| `.capabilities` / `.capability` | Home three-pillar band | White cards, sky border. Gold label → gem → `__icon` → olive benefit with a 2px olive left rule. Flex cards so the three pillars end level under icons of different heights. See §7b |
| `.network-map` | The map (§4) | `__country`, `__link`, `__node--gateway`, `__node--adjacent`, `__gem`, `__label`. Inverts automatically inside `.hero--dark` |
| `.rail` | Value-chain rail | `__stage--here` carries the olive tick |
| `.network-table` | Country comparison | Scrolls inside `__scroll` on narrow screens; `--adjacent` row for Lesotho |
| `.country-panel` | Expandable country profiles | Built on `<details>`, so they open with JS disabled |
| `.network-summary` | The Network — introduction beside the map | Two-up grid, copy left, a map right in a sky card with a gold-ink eyebrow and italic caption — both text lines are the page's own hero subheading, reused rather than rewritten. Stacks at 900px, map capped at 22rem. Built 2026-09-23; the inline SVG (§4) was swapped for the client's `images/network-map.jpg` 2026-09-24, sized via the same `.network-map` class (its base rule — `width:100%; height:auto; display:block` — works for an `<img>` as well as it did for the `<svg>`) |
| `.get-grid` / `.get-list` | The Network — "What you get" | Two-up grid: title + `.get-grid__image` (client illustration, live 2026-09-24) left, a numbered route right in a white card. `.get-grid__image` now carries `.spec-row__panel`'s exact look (sky ground, white border, `--space-4` padding, same radius) — padding on the `<img>` itself stands in for that pattern's separate panel+image pair, since the section is already `.section--sky` and a second identical block class felt like drift, not reuse. Each `.get-list__item` is a gold-outlined gem on a gold-light vertical hairline — the provenance rule turned vertical. Numbers and labels (Route/Sourcing/Delivery) are structural, not client copy; the paragraphs are verbatim. Stacks at 900px. Built 2026-09-23, image styling matched to Sourcing/Trading 2026-09-24 |
| `.image-slot` | Reserved space for an unsupplied photograph/illustration | Off-white ground, sky border, standard card radius — an honest empty box (§7's own rule) rather than a broken `<img>` or a stock photo. Combine with a context class that sets `aspect-ratio` (`--wide` = 16:9). No current users — Sourcing, Trading and Network's `.get-grid__image` all used it before their client artwork arrived (2026-09-24), and the class stays in the CSS for the next unsupplied slot |
| `.hero--split` | Sourcing, Trading and Network heroes | `.hero--page` modifier: sky ground + the sitewide light-band lattice, `.hero__split` grid reused from the home hero for copy-left/figure-right. Built for Sourcing 2026-09-24, reused unchanged on Trading the same day and on Network shortly after — the other three `.hero--page` pages (About, Contact, Provenance) are untouched. Network's hero briefly shipped with only the modifier class added and no `hero--page`/`.hero__split`/`.hero__figure` markup — an in-progress edit found mid-session and completed rather than reverted |
| `.spec-row` / `.spec-row__panel` | Sourcing — specification, network, outcome; also Trading; also Advisory | Three stacked two-up rows on one white section; the non-text side is always a sky panel holding the client's illustration. Row order alternates (image left on "Working the network"/"Documentation") by markup order, not a CSS modifier. Built for Sourcing 2026-09-24; reused unchanged on Trading the same day (hero + three rows) with zero new CSS — same client-supplied-sparkle exception as §7d applies to both pages' illustrations. Sourcing's own panels are all image slots too now — the first-pass `.spec-tags` pill list and `.checklist` component were replaced by client artwork 2026-09-24 and removed from the CSS; the copy that duplicated their content (the spec fields, the three outcomes) still stands as accessible text either way. Advisory reuses the same 1fr/1fr grid for a title/lede split instead of prose/image — `<h2>` left, `.section__intro` paragraph right, no panel — confirming the grid is generic rather than image-specific |
| `.service-card` | Home services | Name, one line, text link pinned bottom |
| `.proposition` | Sky proposition card | Service pages; `__points` two-up grid |
| `.insight-featured` | Hub featured article | Sapphire split panel; right side is the image slot |
| `.insight-card` / `.insight-grid` | Article cards | Border-top rule, olive category, three-up desktop |
| `.masthead` | The Southern Cut hub opener | Publication masthead: name, italic proposition in `--colour-ice-light`, single standfirst paragraph (client-directed shorter copy, 2026-09-25 — see CONTENT.md §4). Documentary ground (Mine.jpg) under the team band's sapphire ramp. No CTAs — the two buttons ("View the trade calendar"/"Explore latest intelligence") were dropped client-directed; `.hub-subnav__jumps` right below already covers the same in-page navigation |
| `.ticker` | "In the trade" strip, hub and homepage | Sits between the header and the masthead. Auto-scrolling — the one deliberate exception to §8's no-looping rule, agreed 2026-09-25. `__track` duplicates its item set once for a seamless loop, skipped under `prefers-reduced-motion` (the animation is already killed sitewide — duplicating would just show every item twice in the static wrapped fallback). `__item` clones `.whats-on`'s `.trade-status` span, but on the sapphire ground its light-ground tokens (`--colour-muted`, `--colour-botswana-ink`) failed AA (2.16:1, 2.21:1) — `.ticker .trade-status--*` overrides to the on-dark tokens §2 already keeps for this job (ice-light, gold-light, 70% white matching the value `.insight-featured__meta` used to prove), so the same badge carries two colour sets by context. `aria-hidden="true"` throughout — every item it shows is also in the accessible `.whats-on` table, so a screen reader is never asked to track moving text. `[hidden]` until JS populates it. On the homepage the items are written in the markup with their own `data-start`/`data-end` (the site runs from `file://`, so no fetching the hub); JS computes each status and drops ended items, so that list is edited by hand alongside the hub table; nothing renders without JS, since the "confirmed" fallback text a no-JS table shows would be redundant duplicated here without the badge that's the ticker's only reason to exist |
| `.hub-subnav` | *Removed 2026-11-15* | Replaced by `.section-nav` (page links, not in-page jumps). See §14 |
| `.pill` | Shared filter-toggle button | One look, three jobs: `.hub-subnav__region`, `.whats-on__filters`, `.intel__filters`. `aria-pressed` per §10's accessibility floor. `--active` modifier: sapphire fill, alpine text; inactive is alpine fill, sapphire border — the same relationship `.button`/`.button--ghost` already use, so a new pair of states doesn't read as a new idiom |
| `.whats-on` | *Removed 2026-11-15* | The table calendar is gone; `.event-row` lists replace it on the hub and Calendar pages. See §14 |
| `.intel` | *Removed 2026-11-15* | Replaced by `.intel-card` / `.intel-grid` (hub pair and the archive). See §14 |
| `.explainer-grid` | "Start with the trade explainers", hub only | Numbered cards (`01`–`06`), replacing the plain `<ul>`. Each card splits one CONTENT.md bullet on its first "? " or ". " into a title (bold) and a description — display formatting only, no new copy. Sits on `.explainer-band` (below); cards stay white with the sitewide 0.3125rem radius and `.engagement__item`'s shadow so they lift off that dark ground |
| `.explainer-band` | Wraps `.explainer-grid`, hub only | Dark gradient close (`.cta-band`'s sapphire > mesh-rise > sapphire sweep) plus the hero's diamond lattice, so the two dark grounds on the page read as one family rather than each inventing its own. `.eyebrow`/`.section__title`/`.section__intro` get the same on-dark overrides `.hero--dark` uses |
| `.brief-band` | "The Southern Cut Brief" newsletter, hub only | Light band (`--colour-sky` + the ink lattice `.section--sky` uses), copy left / white `.form` card right — same `.form` fields and copy as before. Sits between `.explainer-band` above and the dark, flush `.site-footer` below, so the page closes dark → light → dark rather than three dark bands in a row |
| `.article` | Article template | Narrow column, olive category chip with a leading gem, `.article__quote` olive rule, `.article__closing`, `.related` — provenance rule + `.related__title`, two-up (was a plain border and an inline-styled heading; fixed 2026-08-13) |
| `.prose-section` | Service-page prose | Stacked H3 + paragraphs |
| `.about-hero` | About hero | Photograph with the lead notched into its lower left. `__lead` is now a deck: serif opening paragraph, body-type support, `__cta` button. Title is a sentence (2026-09-17), sentence-case at `--size-h1`, capped at 18ch so it stays over the scrim's dark half — the uppercase wordmark treatment went with the old one-word title. Scrim on the section, not the text — see §7c |
| `.approach` / `.approach-card` | Mission & Approach | Collage bleeds off the left edge, cards to the right on `--ground-paper` with the lattice set into each bottom-right corner |
| `.country-tabs` | Home SADC desk | ARIA tabs over the five country briefs: hairline tab row, botswana-ink active rule, the open panel keeps the old tile treatment (`__name`, gold-ink `__orgs` line). Arrow/Home/End keys move tabs; without JS the row stays hidden and the panels stack. Replaced `.country-tile`/`.country-tiles`, removed 2026-09-17 (gateway variant included — see git history). **No longer used on the hub** (2026-09-25) — `.hub-subnav__region`'s pills took over region filtering there and the standalone country descriptions were dropped rather than kept alongside them (client call); the block stays live for Home |
| `.mandate` | About five-step process | `<ol>`, five stages on one gold hairline, a gem at each start — the provenance rule stretched into a sequence. Numbers come from the copy (`__number` in gold ink); markers suppressed. Stacks at 900px |
| `.team-band` | About team | Prose on a photograph. Copy held to `--width-reading` so it stays on the gradient's dark end. Full bios collapse behind `<details>` (`.team-director__profile`, `__toggle` with an outlined gem that fills when open) — JS-free and keyboard-operable |
| `.expect-note` / `.cta-band__list` | About | The olive-ruled differentiating line under the expectations table; the brief's essentials diamond-separated in the closing band (the old credentials-strip treatment, whose block was removed with the 2026-09-17 rebuild). List stacks without separators at 700px |
| `.engagement` | Advisory topics | Five disciplines on a `.section--sky` band, a 6-column span grid (first two cards span 3 each, last three span 2 each) so the lead pair reads broader than the trailing three — not a plain 3-up wrap. Redesigned 2026-09-24: white cards (was sky-filled) with the nav dropdown's shadow (`0 10px 30px rgba(30,58,95,.08)`), each opening with an `.engagement__icon` — a small sapphire line-icon (hand-drawn inline SVG, one per discipline: gem, trend-up, document-check, bar chart, open book) in a sky-tinted circle — above the title. The numbered variant (a botswana-blue `.engagement__number` beside each icon) was tried and removed same-day at the client's request; `.engagement__number` is gone from the CSS, not just hidden. Built 2026-09-17, the block existed in CSS from the start but was unused until this page needed it |
| `.cta-band` | End-of-page CTA | Sapphire, centred, one button. Every sub-page closes with one. Ground is a soft `linear-gradient` sweep (sapphire → `--mesh-rise` → sapphire, 2026-08-13), not a flat fill — same token the hero mesh uses for "sapphire, lifted," so the two dark grounds read as one family rather than two |
| `.contact-channels` / `.channel` / `.form` / `.cal-embed` | Contact | Copy-email button, lazy Cal.com, validated form. Live as of 2026-09-24 — was fully commented out before (front-end only; still no real send, see LAUNCH.md blocker #3). Now: name/company/email/enquiry, a `.form__fieldset`/`.form__legend` "Specification (optional)" group (same 9 fields as `.spec-builder` below, `id="field-*"`), then message. Enquiry options are Sourcing/Trading/Advisory/General — corrected from a stale "Brokerage" left over in the dead code from before that sitewide rename |
| `.spec-builder` | Sourcing — interactive specification form | Replaces the static "What to send" `.network-table` on both Sourcing and Contact. A real `method="get" action="contact.html"` form on Sourcing — deliberately its own block class, not `.form`, so `js/main.js` §03's submit handler (bound to `.form`) can't intercept its plain GET. Submitting needs no JS; the 9 fields plus a hidden `enquiry=sourcing` land in Contact's query string, where `js/main.js` pre-fills the matching fieldset fields via `URLSearchParams` + `form.elements.namedItem()` (first query-string/cross-page state pattern on the site). Shares `.form__row`/`.form__field`/`.form__label`/`.form__input`/`.form__select` unchanged. All fields optional, no validation. Colour (D–Z), Clarity (FL→I3), Cut (Excellent→Poor), Fluorescence (None→Very Strong) and Certification (GIA/IGI/HRD/AGS/Other) options are the standard industry grading scales, not verbatim `CONTENT.md` copy — flagged since nothing enumerates them there yet. Shape options and the Provenance country list are the site's own existing terms, just turned into `<option>`s. Built 2026-09-24 |
| `.site-footer` | Footer | Sapphire; `--flush` when the page ends on a dark band |
| `.membership-line` | Olive membership line | Contact page foot + footer variant |

---

## 7. Imagery

**Photography now ships, supplied by the client.** The rail stages, the service
rows and the About page all carry it. Nothing is sourced by us: stock was
trialled once, every Unsplash candidate came back consumer jewellery, and none
was used.

The original rule was: single stone on dark/neutral ground, loupe, grading tray,
graded parcel, certificate, working tools of the trade — **no people, no
consumer jewellery**. Two client-supplied images break it (§7c). They ship
because the client's comps specify them; the rule stands for anything we choose
ourselves.

The spec's own direction still applies where no image exists: *"if a usable
image does not exist, use type and space instead — an honest empty column beats
a stock photograph."*

Remaining slots, commented in the HTML:
- `.insight-featured__ground` — featured article image
- Article featured images when the CMS arrives

The home hero is no longer a photography slot — see §7a.

Optimise to modern formats, size responsively, alt text on meaningful images, `alt=""` on decorative.

### 7a. The hero mesh ground

`images/mesh.png` is the approved hero comp: a horizontal ramp from sapphire
across the left third, up to a crest at 75% width, then deepening at the right
edge, with a faint diamond lattice over the whole thing. Sampling it on a 9×5
grid showed the vertical variation is negligible — it is a horizontal ramp, not
a true mesh — so it is rebuilt as one `linear-gradient` with stops on the
sampled colours. Rendered output matches the comp exactly at the 50% and 75%
stops.

**Built as CSS, not shipped as the PNG.** The hero's height changes with the
copy and the viewport; a 1440×624 raster would have to stretch to fit.

- The lattice is the supplied `images/diamonds.svg` tiled on `.hero--dark::before`
  at `opacity: 0.1`, matching the comp. It sits on its own layer so the opacity
  applies to the pattern rather than to everything in the hero, which is why
  `.hero--dark` carries `isolation: isolate`.
- `images/diamond-pattern.svg` is not used: its tile is baked onto a fixed
  1440×624 rect whose dimensions are not whole multiples of the pattern's
  211.42×141.42 period, so it seams when repeated.
- **Below 900px the ramp turns vertical**, crest at the top behind the stacked
  map and sapphire arriving by 38% — the map ends at 36% and the gold eyebrow
  starts at 41%. Gold needs the darkest ground available to it; see §13.

### 7b. Capability icons

`capabilities.png` is the approved comp for the home band: three white cards,
each label → gem → line illustration → benefit line. Built as specified, with
three deliberate departures agreed on 2026-08-12:

- **The paragraph is gone**, as the comp shows. Recorded in `CONTENT.md` with
  the removed text kept verbatim — it needs client sign-off, since their note
  was "too wordy", not "remove it".
- **The benefit rule stays olive**, not the comp's `#C1AB87`. Olive is the
  benefit/Insight signature (§2) and the client approved it.
- **The comp's sand ground `#D8CBB6` is not used.** The palette has no warm
  tone; a background image sits behind the band instead —
  `images/mine-in-africa.jpeg`, supplied 2026-09-18. Cards stay opaque white
  on top, so no scrim was needed for text contrast.

The icons are `images/local.svg`, `clearing.svg`, `advisory.svg` — supplied,
141px wide, drawn as a single filled path in `#25446A`. That hex is close to
sapphire but not equal to it; it lives inside the artwork, not in the CSS, so
it is not a new token. If the icons are ever recoloured, take them to
`--colour-sapphire`. Placed at 8.75rem, left-aligned to the card's text edge as
in the comp, and marked `alt="" aria-hidden="true"` — the label and benefit
line carry the meaning.

### 7c. The About page

`about.jpg` is the approved comp, built 2026-08-13 from four supplied assets:
`about-hero.jpg` (1440×700), `4-diamonds.jpg`, `our-team.jpg` and
`small-diamond-pattern.svg`.

**Two of them break §7's subject rule, and it is the client's call, not drift.**
`4-diamonds.jpg` is consumer jewellery — rings and earrings, the exact register
the rebuild set out to escape — and `our-team.jpg` is a stock-looking photograph
of two men in an office. Both appear in the client's own comp, so both ship.
Worth raising at sign-off: the jewellery collage is the single strongest pull
towards retail on the whole site.

**The hero needs its scrim, and the comp does not have one.** The supplied
photograph carries a baked gradient across its left third, but it runs out under
the title — the pit wall there measures **2.21:1** against white and **1.13:1**
against gold. The scrim is a `to right` sapphire ramp on the section (0.95 → 0
by 70%), so the copy clears AA and the picture keeps its right half. Applying it
to the text rows instead leaves a visible horizontal band.

**Type in the hero is white, and the eyebrow `--colour-gold-light`.** Gold ink
would disappear on a dark photograph.

**Mobile needed a structural fix, not just a breakpoint tweak — 2026-08-13.**
`.about-hero__lead` sits in the same grid as the eyebrow and title. At one
column, the lead's own height (it runs to several lines) regularly pushed the
section taller than its `min-height`, which left `align-content: end` nothing
to push against: the heading landed at the very top of the section instead of
the bottom, and the image was only ever visible behind those two short lines of
text — everywhere else, the lead's opaque white covered it. Reported as "the
image isn't showing."

The fix wraps the eyebrow and title in `.about-hero__heading`, sized to its own
`min-height: 18rem` independent of the lead paragraph below it. The image now
shows for a guaranteed band no matter how long the lead runs. The scrim moves
with it — `.about-hero__heading::before`, stops in fixed `rem` rather than
percentages, so it stays anchored to that same 18rem band rather than
stretching across however tall the whole section happens to be. Framing also
moved from `center` to `right`: at mobile's aspect ratio "cover" shows a
narrow vertical slice of a wide photo, and centre landed on the haziest part
of it; right shows more of the terracing.

Desktop is untouched by any of this — `align-content: end` has room to work
there because `min-height: 43.75rem` comfortably exceeds the content's natural
height, so the original per-percentage horizontal scrim (§ above) still
applies as built.

`--ground-paper` `#FBFBFB` is the Mission card ground, sampled from the comp:
off-white rather than sky, so a card reads as raised without taking a tint.

The lattice in each card's bottom-right corner is `small-diamond-pattern.svg` at
50% opacity, on `.approach-card::after`.

### 7d. Sourcing illustrations — sparkle exception, client-supplied

`images/sourcing-hero.jpg` (1327×784, the specification diagram) and
`images/sourcing-network.jpg` (1202×880, the connections diagram) ship with
star/sparkle marks, which §1's "never" list bans. As with the About page's
two departures (§7c), this is the client's own supplied artwork, not
something drawn for the site, so it ships as given — the rule stops rivals
and stock imagery from adding sparkle on our own initiative, not client-
approved illustration. Both render at `width: 100%; height: auto` with no
`object-fit` crop, so the composition (corner marks included) stays intact
at every width. Added 2026-09-24, replacing the `.image-slot` placeholders
built the same day.

## 8. Interaction & motion

- **No unprompted, looping or decorative animation.** Entrance motion is
  permitted: a 200ms fade as a section enters the viewport, and the map's
  one-time draw-in. Nothing loops, nothing pulses, nothing moves on its own
  after it has arrived. Sparkle remains banned.
- **One deliberate exception: `.ticker` on the-southern-cut.html (2026-09-25).**
  The "In the trade" strip auto-scrolls continuously — client-directed, not
  ours to reconsider without asking. It is the only looping motion on the
  site; don't cite it as precedent elsewhere. `prefers-reduced-motion` stops
  the scroll and shows the items as a static wrapped row (§10 still applies
  in full — this exception is to the "no looping" rule only).
- Reveal is gated behind a `.js` class so content is fully visible without
  JavaScript, and every entrance state resolves instantly under
  `prefers-reduced-motion`.
- Interaction feedback is colour/border change only: nav underline, card title colour shift, button darken.
- JS (`js/main.js`) is functional, not decorative: nav toggles, email copy, form validation, lazy Cal.com, Insight filter. Dependency-free; keep it that way.
- Cal.com loads only on user click — never eagerly.

---

## 9. Responsive

Two breakpoints, mobile-first in behaviour:

- **≤900px** — three-column grids (capabilities, services, values, team, insight, engagement) stack to one; country tiles to 2×2; featured article stacks with image on top; footer stacks; the hub's `.whats-on`/`.intel` two-up stacks to one column (calendar first), and `.hub-subnav` stops sticking.
- **≤700px** — country tiles, channels, locations, related, proposition points and form rows to one column; burger nav takes over, dropdown flattens inline; `.hub-subnav__jumps` and every `.pill` group scroll horizontally instead of wrapping; `.brief-band` stacks copy above the form.

Country tiles and service cards keep equal visual weight at every width, per brief.

---

## 10. Accessibility floor

Non-negotiable on every new page or module:

- Semantic landmarks, one H1 per page, logical heading order
- Skip link, `:focus-visible` Botswana-blue outline site-wide
- `aria-expanded` on nav toggles, `aria-pressed` on filters, `role="status"` on form success
- Labels tied to inputs, error text per field, autocomplete attributes
- `prefers-reduced-motion` kills smooth scroll and all transitions
- Contrast: sapphire on white and white on sapphire both pass AA. Check any new colour pairing before use

---

## 11. Phase Two guidance

Inventory and Toolkit must feel native when they arrive:

- **Inventory** — stone cards follow the `.insight-card` pattern (rule-top, quiet, typographic). Filters can follow the removed `.insight-filter` pattern (see git history). No thumbnails of hands, no sparkle. A stone detail page uses the article template's reading discipline: the documentation *is* the content.
- **Toolkit** — calculators use the form vocabulary (`.form__input`, sapphire buttons, sky grounds). Outputs set in Lora like a valuation line, not a dashboard.
- Neither enters navigation until live and maintained.

---

## 12. Divergences from CLAUDE.md code standards

The build departs from the standards in three places. Recorded here so nobody
"fixes" them by accident, and so the next session decides deliberately rather
than drifting further.

| Divergence | CLAUDE.md says | Build does | Status |
|---|---|---|---|
| CSS section labels | `/* \|\| RESET */`, `/* \|\| TOKENS */`, `/* \|\| BASE */`, `/* \|\| UTILITIES */`, `/* \|\| SITEWIDE */`, then page sections | Numbered contents block: `01. Tokens` → `14. Accessibility & motion` | **Unresolved.** Cosmetic but real. Either renumber the CSS or amend CLAUDE.md — don't leave both conventions documented |
| Asset paths | Root-absolute (`/css/main.css`) | Relative (`css/main.css`, `../css/main.css`) | **Deliberate.** Keeps the site openable from `file://` and deployable to a subpath. Revisit only if it moves to a domain root with clean URLs |
| `!important` | Never | ×2 in the `prefers-reduced-motion` block | **Accepted.** The conventional exception — the reduced-motion override has to beat every transition unconditionally. Don't extend it elsewhere |

One further note, not a divergence but a launch decision: fonts load from Google
Fonts via `preconnect` + `display=swap`. That's a third-party request on every
page. Self-host the families if the client wants zero external calls (see
`LAUNCH.md`).

---

## 13. Divergences from the design spec

`lejwana_design_spec.pdf` (July 2026) is the approved spec. The build departs
from it in the following ways. All are decisions, taken deliberately.

| Spec | Build | Why |
|---|---|---|
| Cormorant Garamond + Inter | **Lora + Work Sans** | Client reviewed and approved the live pages as typeset. Kept to avoid re-typesetting approved work. Cormorant appears only in the wordmark, subsetted |
| Signature device is the value-chain rail | **Map is the star; rail built quieter; provenance rule also retained** | Client explicitly asked for the network map. The rail answers a different question and was added at lower volume |
| Split hero, copy left / photography right | **Split hero, copy left / map right** | The spec's right-hand slot was a placeholder awaiting photography. The map fills it and needs no shoot |
| Credentials strip under the hero | **Not built** | Outstanding — see LAUNCH.md |
| Roman numerals i/ii/iii on service cards | **Not built** | Outstanding |
| Benefit line: sapphire text, olive left rule | **Olive text, olive left rule** | Client approved the olive text; the rule was added for the anchor the spec wanted |
| Palette of 8 including Muted | **Muted now added** | Resolved this pass |

### Accessibility debt — cleared 2026-08-13

**Every text pairing and focus ring on all 14 pages passes WCAG AA**, at 1440px
and 390px, measured from rendered pixels.

The three recorded failures are fixed by the ink variants in §2:

| Pairing | Was | Now |
|---|---|---|
| Gold `#B08D57` on white | 3.09:1 | **5.21:1** — `--colour-gold-ink` |
| Gold on sapphire | 3.72:1 | **5.89:1** — `--colour-gold-light` |
| Botswana Blue `#75AADB` on white | 2.46:1 | **5.20:1** — `--colour-botswana-ink`, focus rings included |

Measuring the rendered pages rather than the token table found four more that
the earlier ratio-by-ratio check could not have caught, because each depended
on where the element actually landed:

| Pairing | Was | Now |
|---|---|---|
| Olive on sky, `.membership-line` | 3.95:1 | **4.53:1** — `--colour-olive-ink`. Olive passes on white at 4.54:1, which is why it was missed; the contact page puts it on sky |
| Hero map labels at 80% white, on the crest | 3.41–3.92:1 | **9.0:1** — solid white with a sapphire halo (`paint-order: stroke fill`), so the ramp and lattice behind them stop mattering |
| Maseru label at 50% opacity, light map | 2.05:1 | **5.33:1** — the label no longer dims; the gem still does, and half-scale type carries the subordination |
| Hero country fill at 7% white | lifted the crest to `#4982B0`, capping white type at 4.10:1 | Fill removed in `.hero--dark`. The 70% outlines carry the shapes |

**One non-text item is left, and it is a client decision.** The ice gems on the
map measure **2.77:1** on the crest, under the 3:1 floor for a graphic that
carries meaning. Both fixes change something already approved: outlining the
gems alters the logo's diamond, and darkening `--mesh-crest` breaks the match
to `mesh.png`. Left as ice, because every node is now named by a label that
passes AA. A 0.4-unit sapphire outline was tried and reverted — at render scale
it was subpixel, so it bought no measurable contrast.

Verify with the audit described in `QA.md` after any colour change.

**Re-cleared 2026-09-17, now 16 pages.** Two pairings had regressed since the
08-13 sign-off, both introduced by later work that was never re-audited:

| Pairing | Was | Now |
|---|---|---|
| `.site-footer__copyright` at 50% white on sapphire, all 16 pages | 4.14:1 | **5.24:1** — 60% white. Added 2026-08-18 with the auto-updating year |
| `.hero__highlight` in `--colour-ice` on the mesh rise | 4.09:1 | **4.88:1** — `--colour-ice-light`. Added 2026-09-11 with the new hero copy |

Ice cannot be lightened far enough to pass on the full crest (`#3B79AA` caps it
at 3.88:1 even at 92% lightness). It does not have to: the copy column's
lightest ground is `#385E84`, measured across both viewports. If the hero's
copy ever widens into the crest, this pairing fails again and the audit says so.

**The mesh ground was checked against all three and adds none.** The crest is
the lightest ground anything sits on, so everything over it was re-measured:

| On the crest `#3B79AA` | Ratio | Action |
|---|---|---|
| Map country outlines at 50% white | 2.35:1 (was 4.12:1 on flat sapphire) | **Fixed** — raised to 70% white inside `.hero--dark`, giving 3.16:1, clear of the 3:1 non-text minimum |
| Maseru gem + label at 50% opacity | 2.35:1 (was 4.12:1) | **Fixed** — 70% inside `.hero--dark`. Lesotho stays subordinate through half-scale and the lighter stroke instead |
| Lesotho outline at 22% white | 1.48:1 (was 1.93:1) | Raised to 38% to restore its previous ratio. Still under 3:1 — it was before this pass too, and it is supplementary to Maseru's gem and label |
| Solid white labels and gems | 4.67:1 | **Superseded 2026-08-13.** This row was computed against flat sapphire, but the labels sit over the *crest*, and under the country fill and lattice they fell to 3.41:1. See the debt section above |
| Title, secondary, body copy | 9.0–11.5:1 | All sit on flat sapphire at both widths by design |

Gold was the reason the mobile ramp ends at 38%. Left at 72%, the eyebrow
landed on `#28517A` and dropped to **2.67:1** — worse than the 3.72:1 already
on the books. It now sits on flat sapphire at both widths, so the mesh leaves
the gold debt exactly where it found it.

---

## 14. The Southern Cut rebuild — AGREED 2026-11-15

Source: `BUILD-BRIEF.md` and the three design screenshots (homepage, All intelligence, Calendar) supplied by the client's designer. The designer's colours, sizes and inline styles are visual intent only: everything below maps to existing tokens. **Nothing here is built until this section is marked agreed.** Decisions already taken by the user are marked ✔.

### Pages and URLs ✔
| Page | WordPress | Static | Notes |
|---|---|---|---|
| The Southern Cut (front) | `/the-southern-cut/` | `the-southern-cut.html` | Masthead, lead story + "Next in the trade", latest intelligence + Most read, What's on (next 5). The existing explainers, Weekly Cut band and country jump links stay **below** this layout |
| Intelligence (archive) | `/the-southern-cut/insights/` | `the-southern-cut/insights.html` | Existing URL reused, no redirect needed |
| Calendar | `/the-southern-cut/events/` | `the-southern-cut/events.html` | Existing URL reused |

Both the static pages and the WordPress templates are built and kept in step. Copy ✔: the client's newer intros win over the designer's (Latest intelligence, What's on). Topics ✔: filters are the **existing article categories** (The Region, On Provenance, Rough and Polished, Journeys); the client adds more in WordPress; empty ones are hidden. Explainers stay excluded from the archive. Homepage tags show the article's primary category, not a country. Most read ✔: no view tracking, so a manual "featured" tick in the article editor (up to three).

### Existing blocks reused, no new CSS
`.pill` (all filters, `aria-pressed`), `.trade-status` and its status words, `.intel`/`.intel__item`, `.ticker` (kept as is), `.button--on-dark`, `.eyebrow`, `.cta-band` grounds for the dark panel, `.section--sky`, `.masthead`.

### Gaps: no equivalent in this document. Proposed mapping, each needs a yes or a different answer
1. **Event-type labels** (Result, Tender, Sale, Sight). The designer separates them by colour, but the palette has three ground tints already. Proposal: Result = `--ground-gold` + `--colour-gold-ink`; Tender = `--colour-alpine` with a 1px `--colour-olive` border + `--colour-olive-ink` (the planned olive tint measured 4.49:1, under AA, so the label sits on white instead); Sale = `--ground-icey` + `--colour-botswana-ink`; Sight = `--colour-sapphire` fill, `--colour-alpine` text. No purple. Small uppercase tracked type (eyebrow scale). Contrast checked with `scripts/contrast.py` before build. Any type the client adds later (and the existing "Event" type) gets the neutral `--colour-sky` + `--colour-sapphire` label.
2. **Countdown number** ("13 days" in the dark panel). `--font-heading`, weight 400, one new size token capped at roughly 4.5rem desktop and scaled with `clamp()` down to phone width, alpine on sapphire.
3. **Date block** (month above, large day below, "to 17 Nov" under). A square sky-bordered box: month in `--colour-gold-ink` eyebrow type, day in `--font-heading` at `--size-h2`. No radius beyond the sitewide 0.3125rem.
4. **"Latest" feature card** (full-width, badge, large headline). Proposal: a new `.intel-lead` block on a white card with the sapphire top rule used by `.insight-card`, badge in the Result label style (gold-ink on `--ground-gold`). `.insight-featured` is a split sapphire panel and does not match the design.
5. **Section sub-nav** (The Southern Cut · Intelligence · Calendar). Different from `.hub-subnav` (in-page jumps). Proposal: a new `.section-nav` reusing the header's `.nav__link--active` underline treatment; scrolls sideways at 700px like `.pill` groups.
6. **Event row, calendar icon button, pagination** (Newer / 1 2 3 / Older). Event rows are lists, not table rows, so the existing `.whats-on` table is not reused on these pages. Proposal: `.event-row` built on the tokens above; the icon button is a bordered square using `.button--ghost` colours; pagination uses `.pill` shapes.
7. **Dark "Next in the trade" card**: `.cta-band` gradient tokens, the gold "Add to calendar" button in the design becomes `.button--on-dark` (the palette has no gold button).

### Behaviour (from the brief)
Upcoming only, until end date (or start date) passes; countdowns computed in JS in Africa/Gaborone ("Today", "Tomorrow", "In N days", "On now"), so page caching cannot make them stale. Filters are plain GET links first (`?type=tender&country=namibia`), enhanced by JS. `.ics` per event and one `webcal://` feed for all upcoming events (built; subscribe button stays hidden). Every empty state from the brief is built; the Weekly Cut empty-state line is dropped until a newsletter handler exists (the form sends nowhere today).

### Responsive and accessibility
§9 breakpoints only. Lead headlines scale with the existing `--size-h1`/`--size-h2` clamps. Real `<button>`/`<a>`, icon buttons carry `aria-label="Add {event} to calendar"`, contrast 4.5:1 minimum.

### Content model changes (plugin, not design)
Event type gains **Sight**; new field **Source URL** (stored; linked where shown). "Who can attend" was dropped by the user 2026-11-15. The existing `lj_event` type is extended, not replaced, and all 22 existing events are kept ✔. Social share image: not built (text only for now ✔).

### Built 2026-11-15 — what shipped, and where it differs
- **Blocks added** (all in `css/main.css`, §11): `.section-nav`, `.event-label` (+ `--result`/`--tender`/`--sale`/`--sight`), `.event-list`, `.event-row`, `.event-month`, `.event-filter`, `.event-foot`, `.event-empty`, `.next-card`, `.intel-grid`, `.intel-card` (+ `--lead`), `.front__lead`, `.latest`, `.most-read`, `.pager`. Two tokens added: `--size-countdown`, `--width-date-block`, plus `--width-intel-card`. No new colours, fonts or sizes beyond those.
- **Tender label:** see gap 1. White ground with an olive border, because the olive tint failed AA (4.49:1).
- **Countdown number:** a word ("Tomorrow", "Today", "On now") is set at `--size-h1` so it fits the card; only a day count uses `--size-countdown`.
- **Ticker:** kept. On the hub it is now built from the event rows; the homepage still reads its own markup. Status wording is the new set (Today, Tomorrow, In N days, On now); "Live · day X of Y" is gone.
- **Mobile:** event rows stack (date and title, then the countdown), the pair of intelligence cards drops to one column at 700px, and the filter rows scroll sideways.
- **Audit note:** `scripts/contrast.py` reports the focus ring on the "Add to calendar" button as 1.68:1. That is a measurement artefact: the ring is ice on the dark card, measured by hand at 8:1; the script samples the button's own white face. The homepage hero button, which the script reads correctly, passes.
- **Calendar upkeep:** event countdowns are computed in the browser (Africa/Gaborone), so cached pages stay correct. The "Next in the trade" card is chosen on the server, so clear the page cache daily (LAUNCH.md).

