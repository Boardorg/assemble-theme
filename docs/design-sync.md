# Design sync

Which version of the design the theme last matched. Update this at the end of any session that ports visuals (BUILD-INSTRUCTIONS §7.2).

**How to check for changes:**

1. In the wireframes folder, run `python3 scripts/build-assemble-site-v2.py --check` (rebuild without `--check` if stale).
2. Compare `assemble-site-v2-manifest.json` with the hashes below. A changed page hash means that page's modules changed; a changed guide CSS hash means tokens or shared components changed.
3. If `web-style-guide.html`'s draft number changed, re-diff its `:root` against `assets/css/tokens.css` first.

## Last synced: 2026-10-06

| Source | Value |
|---|---|
| Style guide | `web-style-guide.html`, Draft 0.3 · 2026-10-01 |
| Guide CSS (`guide_css_sha256`) | `904d88942cf79558faef21c897a8d56591a3576705c78ae405310e0d55851f74` |
| Components (`components_sha256`) | `60e12e1d68d7b24ca833d95b9f7fc71c2cad4302e457cc1d15053c20126fb510` |
| Rollup source (`source_sha256`) | `6c420607aa7f80fb34de53d5aaec76b9461e0ce6a4bbe2d2d9c86b45d6005bdc` |

### What the theme has ported

| Module / file | From | Checked |
|---|---|---|
| `assets/css/tokens.css` | guide `#website-system-css` `:root` + `[data-area]` ramps | verbatim copy |
| `assets/css/base.css` | guide `#website-system-css` element defaults and components, image standards v1; `.btn-solid`/`.btn-outline`/`.meta` from `assemble-public-v2.css` | rule for rule |
| `public-masthead` | home mockup + `assemble-public-v2.css` | 1440: every element's position and size identical to the mockup. Drawer (≤980) has no wireframe: on-system placeholder |
| `public-footer` | home mockup + `assemble-public-v2.css` | 1440: 253px tall, identical. Stacks 2-up at 980, 1-up at 640 |
| `assets/svg/icons.svg` | guide `#icon-sprite` | 16 icons |

### Page hashes at last sync

| Page | sha256 (first 12) | Ported |
|---|---|---|
| 1. Home (logged out).html | `6a983f9de5d7` | header/footer only |
| 2. Summits.html | `a4fa6c082769` | — |
| 3. Summit (NALES).html | `616a48ad7e44` | — |
| 3a. Summit (NALES Agenda).html | `7f64c036e271` | — |
| 3b. Summit (NALES Speakers).html | `3bad201cadc0` | — |
| 3c. Summit (NALES Delegates).html | `c68e6b6941a7` | — |
| 4. Article.html | `9e9f37b00af6` | — |
| 5. Playbook.html | `8854a18a226c` | — |
| 6. Create Account.html | `c51eaf4da36b` | — |
| 7. Home (logged in).html | `1c7a4e08e9b6` | — |
| 8. Index (logged in).html | `b1ed2caad115` | — |
