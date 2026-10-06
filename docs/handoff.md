# Assemble theme: handoff

The running build log for the public site. Plan and architecture: `BUILD-INSTRUCTIONS.md` in the project hub (not in this repo). This file is public, so it never holds secrets or personal data.

## Current status

**Phase 0 (setup): in progress.** The three code folders are prepared locally. Nothing is pushed or deployed yet.

| Repo | State |
|---|---|
| `Boardorg/assemble-theme` | Cloned. `.gitignore`, `.deployignore`, deploy workflows and this file added locally. No theme code yet (Phase 1). |
| `Boardorg/assemble-core` | Local repo only, waiting for the GitHub repo to be created. Plugin header, README, workflows. |
| `Boardorg/assemble-content` | Local repo only, waiting for the GitHub repo to be created. Imported from `assemble-field-reports` unchanged, then renamed (folder, main file, header, text domain) with `wp assemble-content` added and `wp field-report` kept as an alias. Contentful scripts in `contentful/`. VIP PHPCS workflow, report-only. |

## Environments

| | Where | Notes |
|---|---|---|
| Local | wp-env, `.wp-env.json` in the hub, http://localhost:8888 | PHP 8.4 and WP 7.0.5 to match the beta. Uses free ACF until the ACF Pro zip is in `vendor-zips/`. |
| Review | `https://assemblebeta.wpenginepowered.com` | PHP 8.4.25, WP-CLI 2.12.0 (checked 2026-10-06). Deploys on push to `main`. |
| Production | theassemble.com | Off limits outside the launch runbook. The production workflow needs the `WPE_PROD_ENV` repo/org variable and a reviewer on the `production` environment. |

## Decisions

- Deploy workflows read the production install name from `vars.WPE_PROD_ENV` instead of hard-coding it, and fail fast if it is unset.
- The one-off Contentful migration scripts (`migrate-takeaways-*`, July 2026, already run) are archived in the private hub, not in `assemble-content`: they delete content and must not be rerun.

## Placeholders

- `.wp-env.json` loads free ACF from wordpress.org. Swap in `./vendor-zips/advanced-custom-fields-pro.zip` once the license arrives.

## Follow-ups

- Waiting on Cale/Mark: create `Boardorg/assemble-core` and `Boardorg/assemble-content`; add the WP Engine deploy key and the `WPE_SSHG_KEY_PRIVATE` secret; set `WPE_PROD_ENV` and a required reviewer on the `production` environment; beta backup point; ACF Pro zip; SSO plugin details; rotate the Contentful CMA token.
- Docker must be running before `npx @wordpress/env start`. Use `npx @wordpress/env`, not `npx wp-env` (an unrelated package).

## Log

- **2026-10-06:** Phase 0 started. Hub folders, `.wp-env.json`, `scripts/wpbeta.sh` and `.env.contentful` set up. Theme cloned; `assemble-core` and `assemble-content` created as local repos. Beta PHP version confirmed as 8.4.
- **2026-10-06:** Local CMS works. wp-env running at http://localhost:8888 with `assemble-content` and `assemble-core` active; `afr_settings` copied from the beta (environment `field-report`, no webhook secret locally); `wp assemble-content sync --all` created all 7 reports and 1 site feature. `/wp-json/wp/v2/field_report` returns 404. No theme code yet, so WordPress falls back to a default theme.
