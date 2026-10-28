# ROADMAP

Source: briefing "PR und Social Media: Markenauftritt und Website" (Lukas, 26.10.2026).
Scope: everything the briefing assigns to **Technik (Christoph)**. All of it is ours. Content, texts and profiles assigned to Ronald (Ivalu) are listed at the end only as dependencies.

Legend: `[x]` done, `[ ]` open, `[~]` partly done / needs input.

## 1. Done

- [x] Local development environment: Herd, PHP 8.3 and 8.5, `composer install`, `npm install`, local `.env`, copy of the production SQLite database (`database/database.sqlite`, git-ignored, contains personal data).
- [x] Production inspected read-only (`ishi-zeus`, vhost `bereal-immobilien.at/httpdocs`). Production code is identical to local `master`; the server's `.git` is outdated (files were deployed by copy).
- [x] PHP 8.5 compatibility: all dependencies install on 8.5, no lint errors, deprecated `PDO::MYSQL_ATTR_SSL_CA` fixed in `config/database.php` (commit `3ca386c`).
- [x] Boost guidelines and skills refreshed (commit `d37b583`). Installed stack: PHP 8.5, Laravel 12, Filament 5, Livewire 4, Flux Pro 2, Tailwind 4, PHPUnit 11.
- Branch: `php-8.5`. Nothing pushed, nothing deployed.

## 2. Findings from analysis

- **Root cause of raw keys and English text:** `akaunting/laravel-language` has `auto => true` (`config/language.php`). A request without `Accept-Language` (Google, AI crawlers) ends up with an empty locale: `<html lang="">`, raw keys in `<title>` (`facility_management.title`), no translation. Same request with `Accept-Language: de` renders correctly. The keys exist in `lang/de.json`.
- **Soft 404:** `Route::fallback(...)` in `routes/web.php` returns `view('404')` with HTTP 200, so old URLs like `/ueber-uns` stay in Google.
- **Non-www redirect** (`bereal-immobilien.at` to `www`) already returns 301 on production, including paths. Briefing item 2 is partly done on the server.
- **Production `robots.txt`** exists (`Allow: /`) but has no `Sitemap:` line. `public/sitemap.xml` is a generated file but is committed to git (local copy contains `breal.test` URLs).
- **Security (not in briefing):** `GET /import` is public and runs `Importer::import()`. Should be protected or removed. Decision needed.
- **Test suite baseline:** 25 pass, 17 fail. The 17 failures are leftover starter-kit tests (`Auth/*`, `Settings/*`, `DashboardTest`) that need `Livewire\Volt`, which is no longer installed. Not caused by our work. Needs a decision (fix or remove) before relying on the full suite.
- **Known production log errors** (Livewire `WithFileUploads` trait missing on navbar/footer components, locked property `rules`, `livewire-dropzone` errors) are not explained yet. Reproduce locally later.

## 3. Work packages

Every package: change, add or update PHPUnit tests, `herd php vendor/bin/pint --dirty`, run the affected tests. Run tests with `herd php vendor/bin/phpunit --configuration phpunit.xml` (plain `php artisan test` fails on the umlaut in the Windows user path).

### WP1 Language and placeholders (implemented on branch `wp1-language-default`, not committed, not deployed)
Briefing: "Sprache, ganze Website", "Platzhalter", "Cookie-Hinweis", "Kleinigkeiten", Technik #1.
- [x] German is the default without `Accept-Language`: `language.auto` set to `false` in `config/language.php`; `app.locale` and `app.fallback_locale` default to `de` (also in `.env.example`); `SetLocale` falls back to `app.locale`.
- [x] `<html lang="de-AT">` for German (public layout `components/layouts/site.blade.php` and `errors/layout.blade.php`); other locales keep their code.
- [x] Translation audit: all dotted keys used in code exist in `lang/de.json`. The only raw placeholders were `unfound` and the 404 button labels; added to `de.json` and `en.json`. New test `TranslationKeysTest` fails on any key used in code but missing in `lang/de.json`.
- [x] Cookie banner: wording fixed ("Dieses Cookie muss", "benötigen Sie"), the obsolete `_gat` cookie is no longer listed (own GA4 registration in `CookiesServiceProvider`, replaces the package's `google()` shorthand).
- [x] Menu duplicates: the doubled labels ("Unternehmensservice", "Kundenlogin") come from `x-button`, which renders its label twice for the hover animation. The decorative copy is now `aria-hidden="true"`. The text still appears twice in the HTML; removing it needs a CSS rework of the button (rebuild of `public/build`), deferred.
- [x] Tests: `tests/Feature/LanguageTest.php` (13 tests with `TranslationKeysTest`). With `language.auto` switched back to `true` 10 of 11 language tests fail, so they guard the fix.
- [x] English under its own `/en` prefix (decided; implemented on branch `wp1-language-default`, not committed):
  - `ApplyLocalePrefix` (global middleware) strips `/en` before routing and records the locale; each page keeps one route and one route name, so `Route::is('makler.*')`, `route:cache` and all existing `route()` calls keep working. `/en`, `/en/` and `/en/kontakt` etc. serve the English page; same slugs as German.
  - Generated links get `/en` back via `URL::formatPathUsing` (`AppServiceProvider`), only for routes in the `localized` route groups (the 30 public page routes in `routes/web.php`). Email token routes (`confirmation`, `solve`), admin, assets and Livewire's update URL stay unprefixed; Livewire restores the locale from the component snapshot, so updates on English pages stay English with prefixed links (covered by a test).
  - `SetLocale` now only reads that locale (default `de`). The package middleware `language` was removed from the public routes, so `?lang=en`, the session and `Accept-Language` no longer switch the public site. (Filament panel routes never ran `SetLocale`; the admin has always been German.)
  - Every public page has `rel="canonical"` (self) and `hreflang` alternates `de-AT`, `en`, `x-default` (`App\Services\LocaleUrls`, `partials/head.blade.php`).
  - The ENGLISH/DEUTSCH switcher in navbar and preheader still shows for logged-in users only and now links to the other language's URL. Showing it to everyone is a product decision, since English content may be incomplete.
  - Tests: `tests/Feature/EnglishPrefixTest.php`. Also verified with `route:cache`.
- [ ] Follow-ups for `/en`: add English URLs to the sitemap (WP4), unknown `/en/...` paths return HTTP 200 like German ones (soft 404, WP4), check English texts/settings (`*_en` settings and translatable models) for gaps, decide whether to expose the language switcher publicly.
- [ ] Makler landing page (`/makler/start`) returns 500 when its settings are empty (`Makler\Parts\Hero` calls `whereIn('id', null)`), so it is not in the test list of `LanguageTest`. Harden or seed settings in tests.
- Verified locally (Herd, production DB copy): no `Accept-Language` gives German titles, `lang="de-AT"`, no raw keys; English header still gives German.
- Done when (verify on production after deploy): `curl` without `Accept-Language` returns German text, no `word.word` placeholders, correct `lang`. Note production `.env` has `APP_FALLBACK_LOCALE=en`; harmless now but should be set to `de`.

### WP2 Titles, descriptions, headings, share preview
Briefing: "Seitentitel und Beschreibungen", "Kleinigkeiten", Technik #4 and #7.
- [ ] Real `<title>` (about 60 characters) and meta description (about 155) per page; proposals for Startseite, Makler, Technik, Team, Karriere are in the briefing (p. 7). Datenschutz and error pages must not be just "bereal".
- [ ] Exactly one `<h1>` per page (start page has none today).
- [ ] Remove `<meta name="keywords" content="HTML, CSS, JavaScript">` from `partials/head.blade.php`.
- [ ] Open Graph tags (title, description, image) on all pages; favicon (a `favicon.png` link exists; verify the file and add `.ico`/touch icon if missing).
- [ ] Canonical URLs: one per page, `www` host, no query noise.
- Done when: a crawler test lists unique title/description/H1 for every route.

### WP3 Links and contact data per section
Briefing: "E-Mail- und Telefonlinks", "Makler, Kontakt", "Makler und Technik, Kontakt und Fußzeile", "Öffnungszeiten".
- [ ] Footer mail link uses `mailto:` (currently `mail:`); Karriere link has `mailto:%20office@...`; telephone links without spaces (`tel:+4315353619`).
- [ ] Makler page: link `office@bereal-makler.at`, not `office@bereal-immobilien.at`.
- [ ] Technik page: `office@bereal-technik.at` as a link, not plain text.
- [ ] Footer and contact area per section show that section's company, e-mail as link, full address "Franz-Josefs-Kai 65/Mezzanin, 1010 Wien" (hyphens, with Mezzanin).
- [ ] Show telephone hours next to opening hours: Mo–Do 9–12 and 13–16, Fr 9–12.
- [ ] Single source for company data (config or settings) so footer, contact, Impressum and structured data cannot drift.

### WP4 URLs, redirects, sitemap
Briefing: "Über uns", "Leistung Baumanagement und Service", Technik #2 and #3.
- [ ] Return real HTTP 404 from the fallback route (and the 404 view).
- [ ] 301 redirects for old URLs (`/ueber-uns` to the right page, anything else found in Search Console or logs). `bontus-eybel.at` to the matching new page if the domain is still reachable.
- [ ] Replace lorem-ipsum slugs (`/leistung/alias-qui-vel-ea-repellendus`, `/service?tab=aliquam-repudiandae-expedita-rerum`) with readable ones (e.g. `/leistung/baumanagement`) and point to be real Technik. Redirect old slugs.
- [ ] Each Leistung under one section only (today `/leistung/x`, `/makler/leistung/x`, `/technik/leistung/x`); other routes redirect or carry a canonical.
- [ ] Sitemap: remove duplicates (99 URLs today), add `lastmod`, build it with the real host. Stop committing `public/sitemap.xml` (generate on deploy or serve from a route).
- [ ] `/makler/ueber-uns` currently renders no content: needs content from the client (WP9) or must be hidden.
- [ ] Server: confirm the `www` redirect covers http and https and all hosts.

### WP5 Structured data (JSON-LD)
Briefing: Technik #5.
- [ ] `Organization` be real Immobilien with the three companies (name, address, phone, e-mail, opening hours, logo, `sameAs` profile links).
- [ ] `RealEstateAgent` for be real Makler.
- [ ] `JobPosting` for each vacancy (`job_vacancies`).
- [ ] `FAQPage` for the FAQ.
- [ ] Validate with Google Rich Results Test after deploy; add tests that the JSON-LD parses and has required fields.
- Needs: social profile URLs for `sameAs` (from Ronald), logo files.

### WP6 Crawler files and HTML content
Briefing: Technik #6 and #8.
- [ ] `robots.txt`: keep `Allow: /`, add `Sitemap: https://www.bereal-immobilien.at/sitemap.xml`. File lives only on the server today; add it to the repo.
- [ ] `llms.txt`: short text with group, companies, services, contact, key links. Text comes from Ronald, we create and serve the file.
- [ ] Verify that all texts (Datenschutz, Über uns, Team) are in the server-rendered HTML without JavaScript. The `datenschutz` route is a Volt/Livewire `policy` component; check with `curl` and fix if empty.

### WP7 Impressum and Datenschutz
Briefing: "Impressum", "Datenschutz".
- [ ] Heading: replace "Angaben gemäß § 5 TMG" with Austrian basis (§ 5 ECG, § 14 UGB, § 25 MedienG).
- [ ] "Franz-Josefs-Kai 65/Mezzanin" everywhere; fix typo "Immobilienmanagment".
- [ ] Technik company: use `office@bereal-technik.at` (not `t.keinrath@bereal-immobilien.at`).
- [ ] Add managing directors and UID for Makler and Technik (data below). Gewerbe, Kammer, Aufsichtsbehörde, Firmenbuchgericht per company: client delivers.
- [ ] Datenschutz page: title (not "Bontus Eybel Immobilien"), text must be present in the HTML. The legal text is checked by the client.

### WP8 Domains, search engines, performance (previously "Christoph's")
Briefing: "Domains", Technik #9 and #10.
- [ ] `www.bereal-makler.at` to `/makler/start` and `www.bereal-technik.at` to `/technik/start` on `www.bereal-immobilien.at` (permanent redirect; Plesk or app-level host redirect). Needs server/DNS access beyond the current read-only access: decide how.
- [ ] Google Search Console and Bing Webmaster Tools: set up if missing, submit the sitemap, give the client read access.
- [ ] PageSpeed Insights for the start page and subpages, mobile first; shrink large images and videos (media is on S3).
- [ ] Remove the stale 597 MB `storage/logs/laravel.log.old` on production (needs approval, production write).

### WP9 FAQ, texts and small content corrections in code or database
Briefing: "Startseite, Why be real?, Our Story", "Referenzen", "Bilder", "FAQ", "Team".
These sit in views, translations or the admin database. Technical part is ours, final wording comes from the client where marked.
- [ ] Texts: "seit fast 30 Jahren", explain "be" as Bachschwöller und Eybel, "Tanja Bachschwöller-Eybel" (Makler page: with ", BA"), remove "Bontus Eybel" everywhere (alt texts, subline, hashtags, domain).
- [ ] Alt texts: German descriptions, no "Bontus Eybel Intro Image", "bereal Makler Image", "animation", no underscores; logos get alt text.
- [ ] References: strip hashtag remnants (`##lovely #farbtupfer ...`), unify "Triester Straße". Includes cleanup of existing database content, to be done as a reviewed migration or admin task.
- [ ] FAQ: fix "European CommissionRat der Europäischen Union", the duplicated lowercase "kontaktieren sie uns", phrase the three non-question entries as questions; add a "Stand" date field. Outdated answers (Wertsicherung, Kurzzeitvermietung, Stromanbieter) need client input.
- [ ] Team pages: show no empty page. Hide until content exists, then render people.
- [ ] Corporate wording rules in UI texts: "be real" always lowercase, Sie-Form, gender "Mitarbeiter:innen" where space is short.

## 4. Reference data (from the briefing)

| | Verwaltung | Makler | Technik |
|---|---|---|---|
| Company (Langform) | be real Immobilienmanagement GmbH | be real Immobilienmakler GmbH | be real Baumanagement GmbH |
| Firmenbuch | FN 152672 v | FN 556967 w | FN 660246 a |
| UID | ATU49490402 | ATU76971617 | ATU82490109 |
| Geschäftsführung | Lukas Eybel | Lukas Eybel, Tanja Bachschwöller-Eybel | Thomas Keinrath |
| E-Mail | office@bereal-immobilien.at | office@bereal-makler.at | office@bereal-technik.at |
| Website | www.bereal-immobilien.at | www.bereal-makler.at | www.bereal-technik.at |

Common: Franz-Josefs-Kai 65/Mezzanin, 1010 Wien, Tel. +43 1 535 36 19 (`tel:+4315353619`), Fax +43 1 535 64 28 (only partly shown, never in social media). Opening hours Mo–Do 8–16, Fr 8–12. Phone hours Mo–Do 9–12 and 13–16, Fr 9–12. Nav order always Verwaltung, Makler, Technik. Main colour #002540, digital font Arial.

## 5. Open decisions and inputs

- ~~English version~~: decided, `/en` prefix, see WP1. Open: expose the language switcher publicly, and who delivers missing English content.
- `GET /import` public route: protect or remove?
- 17 stale starter-kit tests: repair or remove (needs approval per project rules)?
- Domain redirects (`bereal-makler.at`, `bereal-technik.at`) and production writes: how do we get write access, and who deploys? Production is not deployed from git today.
- Production PHP is still 8.3; switching to 8.5 in Plesk is a separate ops step (check extensions `intl`, `exif`, `zip`, `bcmath`, `pdo_sqlite`, php.ini, rollback plan).
- Local images load from the production S3 bucket via public URLs only (dummy keys). Admin login is LinkedIn OAuth and does not work locally.

Inputs from the client / Ronald (not ours, but they block parts above): legal details per company (Gewerbe, Kammer, Aufsichtsbehörde, Firmenbuchgericht), Datenschutz text review, `llms.txt` text, FAQ updates, team content and Über-uns content, social profile URLs, new logo files.
