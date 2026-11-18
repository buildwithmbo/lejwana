# LAUNCH.md — Natural Polished Diamonds · Southern Africa

How the WordPress site goes live. Plan and phases: `docs/WORDPRESS.md`. Checks:
`docs/QA.md`. Phases 1 to 7 are done on the local site; this is phase 8.

## What we hold

Run `sh scripts/build-wp-package.sh`. It writes three files to `dist/`:

| File | Goes to | Purpose |
|---|---|---|
| `lejwana-theme.zip` | Appearance → Themes → Add → Upload | The site |
| `lejwana-core.zip` | Plugins → Add → Upload | Events, article search fields, contact form |
| `lejwana-migration.zip` | SFTP, temporary | Moves the 22 events and 12 articles in. Delete afterwards |

## Cut-over runbook (SiteGround GoGeek, staging first)

SiteGround menu names below are from memory and have not been checked against
your account; follow the SiteGround staging guide linked in `WORDPRESS.md` if they differ.

Do each step on **staging**, check it, then repeat on production or push staging live.

0. **The domain already has a live WordPress site** (Divi, 18 pages, 3 posts, Complianz, really-simple-ssl). staging2 is a copy and was converted first; production gets the same treatment only after sign-off, with a database backup first. Old pages go to draft and the old URLs redirect (end of `wp/redirects.htaccess`).
1. **WordPress** at the site root. PHP 8.4 or later (Site Tools → Devs → PHP Manager). Keep SiteGround's Speed Optimizer and Security Optimizer; add no other plugins.
2. **Upload and activate** `lejwana-core` first, then the `lejwana` theme. Activating the theme creates the pages, categories and the `/the-southern-cut/%postname%/` permalinks.
3. **Settings → Reading:** untick "Discourage search engines" only at go-live (leave it ticked on staging). Check Settings → General: site title, admin email, timezone Africa/Gaborone.
4. **Move the content.** Unzip `lejwana-migration.zip` into a temporary folder on the server, then from that folder run `wp eval-file scripts/migrate-content.php`. Expect "Events migrated: 22" and "Articles migrated: 12". Delete the folder. The theme does not need it.
5. **Redirects.** Paste `wp/redirects.htaccess` above `# BEGIN WordPress` in `.htaccess`. Test a few old URLs (`/about.html`, `/the-southern-cut/on-yield.html`, `/insight`).
6. **Caching.** Site Tools → Speed → Caching: exclude `/contact/` (the form has a nonce). Flush the cache after each change. The Southern Cut front page picks its "Next in the trade" event on the server, so also flush the cache once a day (or exclude `/the-southern-cut/` and the two sub-pages from caching); countdowns on the page recompute in the browser either way. The calendar files are served from `/?lj_ics=<event id>` and the subscribe feed from `/?lj_ics=all` (use `webcal://lejwana.com/?lj_ics=all`); both must stay uncached.
7. **Mail.** Confirm the mailbox `info@lejwana.com` exists. Send a test enquiry from `/contact/` and check it lands in the inbox, not spam. This is the conversion goal, so do not skip it.
8. **Run the checks on the staging URL:** `/usr/bin/python3 scripts/review.py <url>`, `scripts/keyboard.py`, `scripts/check-redirects.py <url>`, Lighthouse on mobile (HTTPS, so best-practices should now pass).
9. **Editor login.** Users → Add New, role Editor, for the client. Send the password separately. Hand over `docs/CLIENT_GUIDE.md`.
10. **Backups and updates.** Site Tools → Security → Backups: confirm daily backups. Turn on auto-updates for WordPress, plugins and the theme in Site Tools → WordPress, or in the WordPress Plugins screen.
11. **Go live.** Push staging to production. The domain is already at SiteGround, so there is no DNS change; confirm the existing mail records are untouched. Enforce HTTPS (Site Tools → Security → HTTPS Enforce).
12. **After:** repeat step 8 on `https://lejwana.com`, submit `https://lejwana.com/wp-sitemap.xml` to Google Search Console, retire the static files.
13. **Acceptance:** the client adds a test event and a test article unaided, then trashes them.

## Open before launch

- **Gaborone address:** placeholder `[CONFIRM: full Gaborone street address]` in the footer (`footer.php`) and the contact locations block.
- **Cal.com link:** placeholder on the contact page.
- **Newsletter (MailerLite):** the Brief form posts to `includes/brief.php`, which calls the MailerLite API. Before it works on a site, add to that site's `wp-config.php`: `define( 'LEJWANA_MAILERLITE_KEY', '<API token>' );` and `define( 'LEJWANA_MAILERLITE_GROUP', '<group ID>' );`. In MailerLite create the custom fields `company` and `role`. Without the key the form shows the failure message. The form carries a nonce, so exclude `/the-southern-cut/` from page caching. Failure messages and privacy wording are written (`CONTENT.md`).
- **Explainers button:** held back until the explainers have a destination.
- **Explainer fact-checks:** 10 `[CONFIRM]` comments inside the explainers (content, so the client sees them in the editor); resolve before publishing.
- **Analytics:** none included.
- **Copy review:** the client reviews copy before it is published (WORDPRESS.md §9).
- **Self-hosted fonts:** currently Google Fonts; decide if zero third-party requests is required.
