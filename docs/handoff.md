# Assemble theme: handoff

The running build log for the public site. Plan and architecture: `BUILD-INSTRUCTIONS.md` in the project hub (not in this repo). This file is public, so it never holds secrets or personal data.

## Current status

**Phase 1: in progress.** Pipeline proven: all three repos deploy to the beta. The beta runs `assemble-content` and `assemble-core`; the new theme is deployed but not yet active (the July `assemble-2026` theme still is).

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
| Production | theassemble.com, WP Engine install `assemble1` (PHP 8.2) | Off limits outside the launch runbook. The production workflow needs the `WPE_PROD_ENV` repo/org variable and a reviewer on the `production` environment. |

## Decisions

- Deploy workflows read the production install name from `vars.WPE_PROD_ENV` instead of hard-coding it, and fail fast if it is unset.
- The one-off Contentful migration scripts (`migrate-takeaways-*`, July 2026, already run) are archived in the private hub, not in `assemble-content`: they delete content and must not be rerun.

- PHP mismatch: beta 8.4, production 8.2. Code must run on 8.2 until production is upgraded (Mark's call). Local wp-env uses 8.2 so incompatibilities show up locally.

## Leak checks

| Date | Where | 1 gate | 2 REST 404 | 3 bad secret 401 | 4 teaser-only feed | 5 anon `?afr_as` |
|---|---|---|---|---|---|---|
| 2026-10-06 | beta, after switch to `assemble-content` | n/a (bypass `open`) | pass | pass (wrong and missing secret) | pass (7 items, no body markers) | n/a (bypass `open`) |

## Placeholders

- `.wp-env.json` loads free ACF from wordpress.org. Swap in `./vendor-zips/advanced-custom-fields-pro.zip` once the license arrives.

## Follow-ups

- Waiting on Cale: `WPE_PROD_ENV` and a required reviewer on the `production` environment; a beta backup point.
- Activate the new theme on the beta (and deactivate Elementor + Elementor Pro): Cale's call, probably after Phase 2 so reviewers don't see the bare placeholder.
- Webhook test: publish a trivial edit in Contentful and confirm it reaches the beta.
- Later: ACF Pro zip (free ACF until then). SSO: Mark is building it; build without it for now (Cale, 2026-10-06). Contentful CMA token rotation: deferred by Cale.
- Docker must be running before `npx @wordpress/env start`. Use `npx @wordpress/env`, not `npx wp-env` (an unrelated package).

## Log

- **2026-10-06:** Phase 0 started. Hub folders, `.wp-env.json`, `scripts/wpbeta.sh` and `.env.contentful` set up. Theme cloned; `assemble-core` and `assemble-content` created as local repos. Beta PHP version confirmed as 8.4.
- **2026-10-06:** Local CMS works. wp-env running at http://localhost:8888 with `assemble-content` and `assemble-core` active; `afr_settings` copied from the beta (environment `field-report`, no webhook secret locally); `wp assemble-content sync --all` created all 7 reports and 1 site feature. `/wp-json/wp/v2/field_report` returns 404. No theme code yet, so WordPress falls back to a default theme.
- **2026-10-06:** Created `Boardorg/assemble-core` and `Boardorg/assemble-content` (public) and pushed all three repos. First beta deploys fail at SSH until the deploy key is added; nothing reached the beta.
- **2026-10-06:** Deploy key added in WP Engine; `WPE_SSHG_KEY_PRIVATE` set as a repo secret on all three repos (the gh login lacks `admin:org`, so not an org secret). First beta deploys succeeded. On the beta, `assemble-content` and `assemble-core` are installed but inactive; `assemble-field-reports` and `assemble-2026` are still active. The theme folder isn't listed as a theme yet because it has no `style.css` (Phase 1).
- **2026-10-06:** Phase 1. Local PHP set to 8.2. `assemble-core`: host-based noindex guard (header, robots meta overriding Yoast, robots.txt) and production-only bypass-off filter. Minimal theme (header, footer, index, Adobe kit, 16px root); IvyOra and Parabolica confirmed loading locally. Beta DB snapshot saved in the hub's `private/beta-db/`. Beta switched from `assemble-field-reports` to `assemble-content` + `assemble-core` in one command: status healthy (7 reports, webhook secret set), dry-run sync 7 unchanged / 0 drafted, `wp field-report` alias works. On the beta: `X-Robots-Tag: noindex, nofollow`, robots meta `noindex, nofollow`, robots.txt disallows all. Leak checks 2–4 pass. WPCode: ZoomInfo and Clarity are drafts (inactive); snippet 2016 is the Power BI benchmark embed, not analytics (inventory it with the benchmark pages, §9.1).
- **2026-10-06:** Cale deleted the old `assemble-field-reports` plugin folder from the beta.
