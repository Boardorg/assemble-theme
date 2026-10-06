# Assemble theme: handoff

The running build log for the public site. Plan and architecture: `BUILD-INSTRUCTIONS.md` in the project hub (not in this repo). This file is public, so it never holds secrets or personal data.

## Current status

**Phase 2: brand foundation built (2026-10-06); not yet visible on the beta** because the new theme isn't active there. **Phase 1: done (2026-10-06).** Pipeline proven: all three repos deploy to the beta. `assemble-content` 1.1.0 has the content-type registry and safe paginated sync. The beta runs `assemble-content` and `assemble-core`; the new theme is deployed but not yet active (the July `assemble-2026` theme still is).

| Repo | State |
|---|---|
| `Boardorg/assemble-theme` | Cloned. `.gitignore`, `.deployignore`, deploy workflows and this file added locally. No theme code yet (Phase 1). |
| `Boardorg/assemble-core` | Created 2026-10-06. Plugin header, README, workflows. |
| `Boardorg/assemble-content` | Created 2026-10-06. Imported from `assemble-field-reports` unchanged, then renamed (folder, main file, header, text domain) with `wp assemble-content` added and `wp field-report` kept as an alias. Contentful scripts in `contentful/`. VIP PHPCS workflow, report-only. |

## Environments

| | Where | Notes |
|---|---|---|
| Local | wp-env, `.wp-env.json` in the hub, http://localhost:8888 | PHP 8.2 (production's version; code must run there) and WP 7.0.5. Uses free ACF until the ACF Pro zip is in `vendor-zips/`. |
| Review | `https://assemblebeta.wpenginepowered.com` (own WP Engine site, "Assemble Dev Site") | PHP 8.4.25, WP-CLI 2.12.0 (checked 2026-10-06). Deploys on push to `main`. |
| Production | theassemble.com, WP Engine install `assemble1` (PHP 8.2). **Launch = fresh start:** the launch-clean beta is copied onto it; nothing in its database is kept. | Off limits outside the launch runbook. The production workflow needs the `WPE_PROD_ENV` repo/org variable and a reviewer on the `production` environment. |

## Decisions

- Deploy workflows read the production install name from `vars.WPE_PROD_ENV` instead of hard-coding it, and fail fast if it is unset.
- The one-off Contentful migration scripts (`migrate-takeaways-*`, July 2026, already run) are archived in the private hub, not in `assemble-content`: they delete content and must not be rerun.

- PHP mismatch: beta 8.4, production 8.2. Code must run on 8.2 until production is upgraded (Mark's call). Local wp-env uses 8.2 so incompatibilities show up locally.

## Leak checks

| Date | Where | 1 gate | 2 REST 404 | 3 bad secret 401 | 4 teaser-only feed | 5 anon `?afr_as` |
|---|---|---|---|---|---|---|
| 2026-10-06 | beta, `assemble-content` 1.1.0 (registry) | n/a (bypass `open`) | pass | pass | pass (7 items, no body markers) | n/a (bypass `open`) |
| 2026-10-06 | beta, after switch to `assemble-content` | n/a (bypass `open`) | pass | pass (wrong and missing secret) | pass (7 items, no body markers) | n/a (bypass `open`) |

## Placeholders

- **Site Settings** are defaults in code (`assemble-core/inc/site-settings.php`), read through `assemble_site_setting()` / the theme's `assemble_setting()`. The SCF (or ACF) options page plugs in later without template changes.
- **Footer links:** wireframe items without a page yet are left out (Press, Newsletters, Privacy/Terms as separate pages); Legal points at `/policies/`. LinkedIn is the live site's URL.
- **Masthead nav fallback** (until `wp assemble setup` creates the menu): Insights → `/field-reports/`, Communities → `/field-reports/` (placeholder: no communities index yet), Summits → `/summits/`.
- **Mobile drawer, search, logged-in masthead state:** no wireframe. Built plain and on-system; flag for design. Search links to the default WordPress search; logged-in users see one "Account" button.
- **Block editor palette** is the neutrals only; practice colours come from `data-area`, not from editors.

- Custom fields plugin: **Secure Custom Fields** (WordPress.org's ACF fork, free; includes repeater, options pages, flexible content). `.wp-env.json` loads it. Beta still runs ACF free 6.8 until Cale/Mark OK the swap; production gets SCF with the launch copy.

## Follow-ups

- Waiting on Cale: `WPE_PROD_ENV` and a required reviewer on the `production` environment; a beta backup point.
- Activate the new theme on the beta (and deactivate Elementor + Elementor Pro): Cale's call, probably after Phase 2 so reviewers don't see the bare placeholder.
- **Next session (Phase 3, first items):** design sync after Cale's wireframe cleanup; swap the beta from ACF to Secure Custom Fields (steps in BUILD-INSTRUCTIONS Phase 3); then the SCF Site Settings page. Mark to sanity-check SCF (decision #2). SSO: Mark is building it; build without it for now (Cale, 2026-10-06). Contentful CMA token rotation: deferred by Cale.
- Docker must be running before `npx @wordpress/env start`. Use `npx @wordpress/env`, not `npx wp-env` (an unrelated package).

## Log

- **2026-10-06:** Phase 0 started. Hub folders, `.wp-env.json`, `scripts/wpbeta.sh` and `.env.contentful` set up. Theme cloned; `assemble-core` and `assemble-content` created as local repos. Beta PHP version confirmed as 8.4.
- **2026-10-06:** Local CMS works. wp-env running at http://localhost:8888 with `assemble-content` and `assemble-core` active; `afr_settings` copied from the beta (environment `field-report`, no webhook secret locally); `wp assemble-content sync --all` created all 7 reports and 1 site feature. `/wp-json/wp/v2/field_report` returns 404. No theme code yet, so WordPress falls back to a default theme.
- **2026-10-06:** Created `Boardorg/assemble-core` and `Boardorg/assemble-content` (public) and pushed all three repos. First beta deploys fail at SSH until the deploy key is added; nothing reached the beta.
- **2026-10-06:** Deploy key added in WP Engine; `WPE_SSHG_KEY_PRIVATE` set as a repo secret on all three repos (the gh login lacks `admin:org`, so not an org secret). First beta deploys succeeded. On the beta, `assemble-content` and `assemble-core` are installed but inactive; `assemble-field-reports` and `assemble-2026` are still active. The theme folder isn't listed as a theme yet because it has no `style.css` (Phase 1).
- **2026-10-06:** Phase 1. Local PHP set to 8.2. `assemble-core`: host-based noindex guard (header, robots meta overriding Yoast, robots.txt) and production-only bypass-off filter. Minimal theme (header, footer, index, Adobe kit, 16px root); IvyOra and Parabolica confirmed loading locally. Beta DB snapshot saved in the hub's `private/beta-db/`. Beta switched from `assemble-field-reports` to `assemble-content` + `assemble-core` in one command: status healthy (7 reports, webhook secret set), dry-run sync 7 unchanged / 0 drafted, `wp field-report` alias works. On the beta: `X-Robots-Tag: noindex, nofollow`, robots meta `noindex, nofollow`, robots.txt disallows all. Leak checks 2–4 pass. WPCode: ZoomInfo and Clarity are drafts (inactive); snippet 2016 is the Power BI benchmark embed, not analytics (inventory it with the benchmark pages, §9.1).
- **2026-10-06:** Cale deleted the old `assemble-field-reports` plugin folder from the beta.
- **2026-10-06:** Webhook test passed: Cale published a cloned `[test] … Tacos` entry in Contentful; the beta created post 2276 at 22:02:47 via `ContentManagement.Entry.publish` and serves it (200). Phase 1 pipeline checks complete except the registry work.
- **2026-10-06:** Unpublish test passed: unpublishing the `[test] … Tacos` entry moved beta post 2276 to draft via `ContentManagement.Entry.unpublish`.
- **2026-10-06:** `assemble-content` 1.1.0: content-type registry (`AFR_Types`, filter `afr_content_types`), paginated CDA fetch ordered by `sys.id` with de-duplication, orphan drafting only after a complete fetch and only within the synced type, synced types hidden from wp-admin (status screen at Settings → Assemble Content), `sync --type`, `types` command, dry run lists drafts. Local: forced re-sync left all 7 posts byte-identical (`tests/fingerprint.php`); `tests/sync-test.php` passes (150 entries over 2 pages; failed, short and shifted pages draft nothing; complete fetch drafts real removals; type isolation). Yoast sitemap still lists Field Reports. Beta: dry run 7 unchanged / 0 drafted, `--entry` path OK, leak checks 2–4 pass. Phase 1 complete.
- **2026-10-06:** Phase 2 brand foundation. `tokens.css` (verbatim from guide Draft 0.3) + `base.css` (guide components rule for rule) + `theme.json` v3 (palette, IvyOra/Parabolica, type and spacing scales, custom colours off). Icon sprite (16 icons), logo SVG (`currentColor`), image sizes for the IMG slots, labels in one place (`assemble_label()`), Site Settings reader in `assemble-core`. Modules `public-masthead` (with phone/tablet drawer) and `public-footer`: at 1440 both measure identical to the mockup; checked at 980 and 375 (no horizontal scroll; drawer opens, closes on Escape, returns focus). Local PHP 8.2 lint clean. `docs/design-sync.md` records the manifest hashes.
- **2026-10-06:** Evaluated Secure Custom Fields 6.9.5 locally: 37 field types incl. repeater, flexible content, gallery, clone; options pages, blocks, local JSON, `get_field()` all present; it auto-deactivates ACF. Site Settings code works unchanged. Local now uses SCF instead of ACF Pro.
- **2026-10-06:** Launch decided (Cale): **fresh start.** The beta becomes production via WP Engine "Copy environment"; no member accounts (Ultimate Member dropped), no Elementor pages carry over. Only the URLs worth keeping (BUILD-INSTRUCTIONS §9.1) are rebuilt or redirected. So the beta must be launch-clean before the copy, and there is no plugin/settings work on production. BUILD-INSTRUCTIONS §1, §3.2, Phase 7, the runbook, §9.1 and decisions #3/#11 updated.
- **2026-10-06:** BUILD-INSTRUCTIONS updated with a "Where we are" snapshot, Phases 0–2 ticked, SCF replacing ACF Pro (§5.4), and the beta SCF swap planned as Phase 3's first step. Phase 3 (Homepage) starts in a new session after Cale's wireframe cleanup.
