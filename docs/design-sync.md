# Design sync

Which version of the design the theme last matched. Update this at the end of any session that ports visuals (BUILD-INSTRUCTIONS §7.2).

**How to check for changes:**

1. In the wireframes folder, run `python3 scripts/build-assemble-site-v2.py --check` (rebuild without `--check` if stale).
2. Compare `assemble-site-v2-manifest.json` with the hashes below. A changed page hash means that page's modules changed; a changed guide CSS hash means tokens or shared components changed.
3. If `web-style-guide.html`'s draft number changed, re-diff its `:root` against `assets/css/tokens.css` first.

## Last synced: 2026-10-09 (Draft 0.4; Phase 5a Summits)

2026-10-09 check: `--check` reports the rollup current; guide CSS, components, rollup source and built rollup hashes all identical to the 2026-10-07 values below. Ported "2. Summits" from the rollup's `wireframeData`.


2026-10-07 check: guide CSS, components and every public page source unchanged. The built rollup and builder hashes changed only because of the Member Center / Network split (2026-10-07): diffing the rollup's `wireframeData` against the archived `2026-10-07-before-network-split` build shows all 11 public entries byte-identical, including "4. Article". Nothing to port.

| Source | Value |
|---|---|
| Style guide | `web-style-guide.html`, Draft 0.4 · 2026-10-06 |
| Guide CSS (`guide_css_sha256`) | `989685b0079b237ca0c801d958f137dbfc797583fd8eda1200e844e397b6ecec` |
| Components (`components_sha256`) | `8765eaee10d750f771ffa81b56c9e3f0ece8cafe2ab193caf9b6437212a784c0` |
| Rollup source (`source_sha256`) | `6c420607aa7f80fb34de53d5aaec76b9461e0ce6a4bbe2d2d9c86b45d6005bdc` |
| Built rollup (`assemble-site.html`, sha256) | `fb0025c6e597d4f16db239f80ad5ef0871d7efd9c556af8408c9100fcea11a5b` (was `5a242b98…`) |
| Builder (`scripts/build-assemble-site-v2.py`, sha256) | `e7beffb094efaa4dc1d4c6f345f4e3a4d278887be126f05aaec21d815ce6c3d2` (was `a6999594…`) |

**Why the last two rows:** since 2026-10-06 most page edits live in the builder's `review_edits` pass, not in the standalone page files, so the per-page source hashes below don't move when a page's design changes. Compare the built rollup's hash (`shasum -a 256 assemble-site.html`) as well; if it changed, diff the extracted page against the theme.

### What the theme has ported

| Module / file | From | Checked |
|---|---|---|
| `assets/css/tokens.css` | guide `#website-system-css` `:root` + `[data-area]` ramps | verbatim copy (Draft 0.4 changed no token values) |
| `assets/css/base.css` | guide `#website-system-css` element defaults and components, image standards v1; `.btn-solid`/`.btn-outline`/`.meta`/`.link-arrow` size and the named display variants from `assemble-public-v2.css` | rule for rule; Draft 0.4: 14px floor, centred button labels, `.link-arrow`, `.breadcrumbs` |
| `public-masthead` | home mockup + `assemble-public-v2.css` | 1440: every element's position and size identical to the mockup. Drawer (≤980) has no wireframe: on-system placeholder |
| `public-footer` | home mockup + `assemble-public-v2.css` | 1440: columns identical; Draft 0.4 logged-out create-account row added (measured identical). Stacks 2-up at 980, 1-up at 640. YouTube waits on a URL |
| `home-top-stories` | home mockup (rollup) | 1440: card, grid (793/397), image, tag, headline and side-card boxes identical to the mockup; real headlines run longer than the sample copy |
| `peer-intelligence-splash` (+ free-account CTA) | home mockup | 1440: band, grid (663/599), statement and CTA card identical. Art slot is an empty frame (no image in the design yet) |
| `content-explorer` (`explore-peer-intelligence`, `topic-navigation`, `ld-insights-card`, `ld-benchmark-card`, `ld-summit-card`) | home mockup + guide gallery | 1440: heading, pill rows (48px pills), intro and card styles match. Next Summit added in Phase 5a. Source card, Working Sessions and Leaders not built (no data source) |
| `learn-more-band` | home mockup | 1440: identical boxes. Rendered once below the panels, in neutral |
| `assets/svg/icons.svg` | guide `#icon-sprite` | 16 icons |
| `article-hero` (breadcrumbs, content head, share row, inline author, hero image) | article mockup (rollup) | 1440: breadcrumbs, hero (1120), tag, title (637×145), dek (960), date, share row, meta row and hero image (1120×560) boxes identical. 980/375: no sideways scroll; title 34px at ≤640 |
| `article-body-with-aside` | article mockup | 1440: layout 1120, body 720, aside 328 sticky, h2 30/34.5 accent ink, blockquote, aside card and button identical. Field Report sections (Story in Brief, takeaways list, council expander, engagement cards) are on-system additions |
| `article-author-bio` | article mockup | 1440: full width 1296, portrait 110×130, h3 25/30 identical |
| `related-peer-intelligence` (+ `report-card`) | article mockup | 1440: grid 1120, cards 361, thumb 323×182, h3 20/25 identical. The same card fills the Insights archives |
| `account-cta` | article mockup | Ported from the mockup's rules (logged-out only, so not measured in the admin session) |
| `report-gate`, `report-archive` | — | No wireframe: on-system, flagged in the handoff |
| `summits-hero` | Summits mockup (rollup) | 1440: grid 690/558, kicker, h1 (531×206, 70/.98, −.02em), intro (653) and 16:9 image (558×314) identical; 56px below the masthead as in the mockup. Stacks at ≤1100 |
| `summits-selector` | Summits mockup + `content-explorer` pills | 1440: section rules, heading 30/34.5, pills 44 tall with 16px padding, rows 18 apart. About 10px shorter than the mockup (its row wrapper has extra space) |
| `summits-practice-detail` (+ `event-card`, `free-account-cta`) | Summits mockup | 1440: intro card (min 254, 44 padding, h2 42), Featured Summits head (25/30), event cards 420×270 with centred button, free-account CTA 1296×109 identical. The "Source of peer intelligence" panel, Summit talks and Leaders to follow aren't built (no data source) |
| `summits-matrix` | Summits mockup | 1440: rows 250/770/174 with a 4px area rule, eyebrow 12/700, title 20/25 in accent ink, date line 16/24 500, blurb 16/1.55, button 162×44 identical; rows run taller where EP's titles are longer. One column at ≤760 |
| `ld-summit-card` (Next Summit) | home mockup | Title, dates, place, button and "See all Summits" from the mockup; the photo placeholder holds the summit's colour logo on a sunken 16:9 frame |

### Page hashes at last sync

| Page | sha256 (first 12) | Ported |
|---|---|---|
| 1. Home (logged out).html | `6a983f9de5d7` | header, footer, homepage modules (2026-10-06, rollup with content review edits) |
| 2. Summits.html | `a4fa6c082769` | summits modules (2026-10-09, rollup with content review edits) |
| 3. Summit (NALES).html | `616a48ad7e44` | — |
| 3a. Summit (NALES Agenda).html | `7f64c036e271` | — |
| 3b. Summit (NALES Speakers).html | `3bad201cadc0` | — |
| 3c. Summit (NALES Delegates).html | `c68e6b6941a7` | — |
| 4. Article.html | `9e9f37b00af6` | article modules (2026-10-07, rollup with content review edits) |
| 5. Playbook.html | `8854a18a226c` | — |
| 6. Create Account.html | `c51eaf4da36b` | — |
| 7. Home (logged in).html | `1c7a4e08e9b6` | — |
| 8. Index (logged in).html | `b1ed2caad115` | — |
