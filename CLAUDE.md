# CLAUDE.md — Natural Polished Diamonds · Southern Africa

Operating guide for working on this project in Claude Code. Read this first,
then `docs/DESIGN.md` before touching any styling, then `docs/SESSION_LOG.md`
for history.

## What this is

Lejwana needs a clean, institutional website that positions the business as a Southern African natural polished diamond house.

The website should lead with Southern Africa, explain what Lejwana does in clear trade language, and establish an Insight section that demonstrates market knowledge.

The visual reference is the clean, restrained style of Bonas Group and I. Hennig & Co. The site should feel like a serious trade house rather than a jewellery retailer.

The audience is one persona: [name, age, situation — what they're buying
emotionally, not just functionally].

The conversion goal is to generate leeads.

## How we work


1. Before building anything, the relevant doc must exist and be agreed.
2. `docs/DESIGN.md` is the single source of truth for the look. The CSS
   *implements* it — it does not invent it.
3. Update `docs/SESSION_LOG.md` at the end of every session (run `/wrap`)
   so the next one has continuity.

## Build order

- Build section by section, top of page downward, using styling from
  `docs/DESIGN.md`. Responsive, accessible.
- After each section, run the relevant `docs/QA.md` checks on it, log
  progress in `docs/SESSION_LOG.md`, then move to the next section.

## Document index — open the right file for the task

| When you're… | Read |
|---|---|
| Building or styling anything | `docs/DESIGN.md` |
| Placing copy into markup | `docs/CONTENT.md` + the **Copy — not your job** rules below |
| Testing before launch | `docs/QA.md` |
| Deploying the site live | `docs/LAUNCH.md` |
| Picking up where we left off | `docs/SESSION_LOG.md` |
| Moving The Southern Cut to WordPress | `docs/WORDPRESS.md` |

## Copy — not your job

All page copy is written outside this project and arrives finished in
`docs/CONTENT.md`. Use it **verbatim**. Do not write, rewrite, "improve",
or pad copy.

- If a section needs words that `CONTENT.md` doesn't have, insert
  `[CONFIRM: what's needed]` in the markup and flag it — never draft your own.
- Never invent stats, testimonials, review counts, client names, or
  portfolio pieces.
- Microcopy you do own (aria-labels, alt text, validation messages):
  British English, plain, specific. "Enter your email address", not
  "Oops! Something went wrong!".

## Code standards

**HTML**
- Semantic elements first: header, nav, main, section, article, footer.
  div only when no semantic element fits.
- No wrapper divs that exist purely for styling hooks — style the semantic
  element.
- One h1 per page. Heading levels never skip (h1 → h2 → h3), so screen
  readers and search engines read the structure correctly.
- All images get width, height, and alt. Decorative images:
  `alt="" aria-hidden="true"`.
- No inline styles. No inline event handlers (onclick etc).
- Comment sparingly: one short `<!-- comment -->` per section boundary at
  most. Nothing that restates the markup.
- Class names: lowercase, descriptive, consistent — the same thing gets the
  same name everywhere.
- Two-space indentation, one attribute style throughout. Validate against
  the W3C validator before QA sign-off.

**CSS**
- One stylesheet: `css/main.css`, organised into labelled sections in a
  fixed order: `/* || RESET */`, `/* || TOKENS */`, `/* || BASE */`,
  `/* || UTILITIES */`, `/* || SITEWIDE */`, then one block per page
  section in page order.
  **The build does not follow this** — it uses a numbered `01. Tokens` →
  `14. Accessibility & motion` scheme. Unresolved; match the file you're
  editing and see `docs/DESIGN.md` §12 before changing either.
- Custom properties for all colours, spacing, and type — defined once in
  `:root` under `|| TOKENS`. Never hardcode a hex that already exists as
  a `--var` (see `docs/DESIGN.md` for the token list).
- Class naming: BEM.
- Flat selectors. Max nesting depth: 2. No IDs as selectors. No
  overly-specific selector chains.
- No `!important`. If you think you need it, restructure the cascade
  instead.
- Mobile styles are the default; `min-width` media queries only.
- No utility framework, no resets beyond the `|| RESET` block.
- Comment decisions, not declarations. If a value or approach exists for a
  non-obvious reason, say why in one line. Everything else stays
  uncommented.

**JS**
- Vanilla ES modules. No build step, no npm dependencies unless approved
  first.
- GSAP or Three.js only when the animation genuinely needs it — CSS
  transitions first.
- No classes for things a function can do. No abstraction until a pattern
  repeats 3 times.
- Functions under ~30 lines, one job each, max 3 parameters. Guard clauses
  over nested ifs; max nesting depth 2.
- Clear, intentional naming — verbs for functions (`openMenu`), nouns for
  values (`menuButton`). If the name needs a comment, rename it.
- querySelector caching: query once, store in a const at the top of the
  module.
- Progressive enhancement: the page must work with JS disabled.
- No duplicated logic — extract on the third repetition, not the first.

**General**
- Prefer deleting code over adding it. If two approaches work, pick the
  shorter one.
- Never add "just in case" code, config, or comments explaining obvious
  things. Let the code speak for itself.
- No hard-coded magic values — name them (CSS custom property or JS const).
- Match the existing file's formatting exactly when editing.

## Hard rules — do not break these

1. **Never invent content.** No made-up stats, testimonials, review counts,
   client names, portfolio pieces, or filler copy. Missing content gets
   `[CONFIRM: …]` in the markup and a flag to the user — always.

2. **British English in all microcopy and code identifiers.** "-ise"
   spellings, "colour" in prose (CSS properties stay `color`).

3. **No console errors. Verify before claiming done.** Every change is
   screenshotted at 1440px and 390px with a headless browser before being
   called finished (see Verification below).

4. **`prefers-reduced-motion` must keep working.** All motion has a
   reduced-motion fallback that reveals content statically. Don't add
   motion without honouring it.

## Tech stack

- **Vanilla HTML/CSS/JS**, no framework, no bundler, no npm.
- `index.html`, `css/main.css`, `js/main.js`, linked **relatively** — the site
  runs from `file://` and from a subpath. See `docs/DESIGN.md` §12.

## Verification (required before saying a change is done)

Run `scripts/review.py` (headless Chromium via Playwright): screenshots at
desktop 1440px and mobile 390px, captures console + page errors, scrolls
the full height first so every ScrollTrigger fires, then shoots.

Check: (1) zero `pageerror` / console errors, (2) desktop layout intact,
(3) mobile layout intact — headline not clipped, form usable, sections
stacked. Downscale tall screenshots before viewing (the viewer caps at
8000px). Only present to the user after this passes.

## Deliverable location

Repo root. Eight pages plus six articles in `the-southern-cut/`, sharing
`css/main.css` and `js/main.js`. There is no build step — these files are the
deployable output. File map in `docs/DESIGN.md` §0.

---
Scaffolded 2026-08-11 with blkswan-new v2.
