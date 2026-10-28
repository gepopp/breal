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
- **Test suite baseline (at the start):** 25 pass, 17 fail; the failures were leftover starter-kit tests, since removed (see WP4). Suite now 169 tests, all passing.
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
- [x] Makler landing page (`/makler/start`) returned 500 when its settings were empty (`Makler\Parts\Hero` called `whereIn('id', null)`, the about image was null). Fixed during WP2; the page is back in the `LanguageTest` list and covered by `PageMetaTest`. The property search crashed on an empty realties table too (fixed).
- Verified locally (Herd, production DB copy): no `Accept-Language` gives German titles, `lang="de-AT"`, no raw keys; English header still gives German.
- Done when (verify on production after deploy): `curl` without `Accept-Language` returns German text, no `word.word` placeholders, correct `lang`. Note production `.env` has `APP_FALLBACK_LOCALE=en`; harmless now but should be set to `de`.

### WP2 Titles, descriptions, headings, share preview
Briefing: "Seitentitel und Beschreibungen", "Kleinigkeiten", Technik #4 and #7.
Status 2026-10-28: implemented and tested locally (not committed, not deployed). The crawler test is `tests/Feature/PageMetaTest.php`.
- [x] Real `<title>` and meta description on every page, German and English, all unique. The briefing's proposals are used verbatim for Startseite, Makler, Technik, Team and Karriere. Texts live in the language files (`meta.<page>.title|description`; `meta.makler.*` and `meta.technik.*` override on those sections); `App\Services\PageMeta` selects them and builds titles for dynamic pages: competences ("<name> – be real Verwaltung"), FAQ answers, job ads ("… – Karriere bei be real") and properties (ALL-CAPS titles become title case; description from `freitexte.objektbeschreibung`). Titles are at most 60 characters (the home title from the briefing has 63), descriptions about 77 to 155; texts are cut at word boundaries and stripped of HTML. The unused old title/description keys were removed.
- [x] Fixed on the way: property pages had the title `bereal` (the component read `titel`, the field is `title`); FAQ detail, Unternehmensservice and 404 had `bereal`; two description tags per page; "Immobilienmanagment" typo in the Impressum and Barrierefreiheit headings (WP7).
- [x] Exactly one `<h1>` on every page: the main heading of each page (hero of the three start pages, Leistungen, Service, Karriere, Kontakt, Team, FAQ, FAQ detail, Immobiliensuche, competence and job pages, 404) now renders as `<h1>` (no visual change; the heading component already supported `level="1"`). Exception: `/makler/ueber-uns` is an empty scaffold (WP9/WP6) and has no H1.
- [x] `<meta name="keywords">` removed.
- [x] Open Graph (`og:title|description|url|type|locale|site_name|image` with width, height, alt) and Twitter card (`summary_large_image`) on all pages; the image is chosen per section: `public/og-image.png` (be real Immobilien, brand blue), `og-makler.png`, `og-technik.png` (1200x630, white section logo on the section colour, rendered from the SVG logos). `<meta name="theme-color" content="#002540">`.
- [x] Favicon: `public/favicon.ico` was an empty (0 byte) file; now a valid multi-size icon (16/32/48) built from `favicon.png`, plus `apple-touch-icon.png` (180x180) and the links in the head.
- [x] Canonical: one per page with the `www` host (APP_URL on production), no query noise, self-canonical per service tab and per language.
- [x] 404 page: own title/description, H1, `noindex` (the HTTP status is still 200, WP4).
- [x] Hardening found by the crawler test (empty database): Makler about image, property search with no properties; `config('app.default_locale')` so a request to `/en` cannot leak English into later requests of the same process (tests, queue workers).
- [ ] **Placeholder, needs delivery from Ronald (see "Deliverables needed from Ronald / the client" in section 5):** the share images `public/og-image.png`, `og-makler.png` and `og-technik.png` are a technical placeholder (white section logo on the section colour, generated by us). Final designed images are to be supplied (1200 x 630 px, PNG or JPG, one per section) and dropped in under the same file names; no code change needed. A per-page image for properties (first photo) is possible later. The Makler and Technik FAQ pages show the Hausverwaltung questions (same content on all three), so their meta text is generic.
- [ ] Open: per-service titles on `/service?tab=<slug>` (all tabs share the page title).

### WP3 Links and contact data per section
Briefing: "E-Mail- und Telefonlinks", "Makler, Kontakt", "Makler und Technik, Kontakt und Fußzeile", "Öffnungszeiten".
Status 2026-10-28: implemented and tested locally (not committed, not deployed). Tests: `tests/Feature/CompanyContactDataTest.php` (21 tests).
- [x] **Single source for company data:** `config/company.php` (address "Franz-Josefs-Kai 65/Mezzanin, 1010 Wien", phone, fax, opening and phone hours, and per section: short name, legal name, e-mail, website, Firmenbuch, UID, managing directors, all from the briefing) and `App\Services\CompanyInfo` (`section()`, `address()`, `phoneUri()`, `telUri()`, `mailtoUri()`, localized `hours()`). Footer, contact area, property contact and e-mails read from it; WP5 (structured data), WP7 (Impressum, three companies with data) and `llms.txt` should use it too.
- [x] Footer: `mailto:` instead of `mail:`; phone as `tel:+4315353619`; the company of the current section (name, legal name in the copyright line, e-mail); full address; opening hours (Mo–Do 08:00–16:00, Fr 08:00–12:00) and the new phone hours (Mo–Do 09:00–12:00 und 13:00–16:00, Fr 09:00–12:00), German and English labels. The section is stored as component state, so the footer stays correct in Livewire updates.
- [x] Contact area: one block for the current section instead of three copies; the Makler block linked the Verwaltung address (wrong field) and the Technik data came from stale settings: both fixed, address now with "/Mezzanin". Property contact box uses the Makler data.
- [x] Links in admin-entered texts are repaired when rendered (`App\Services\HtmlLinks`, applied in `BaseSettings`): `mail:` to `mailto:`, `mailto:%20`/space/`&nbsp;` removed, spaces and separators removed from `tel:` links, "Franz Josefs Kai 65" to "Franz-Josefs-Kai 65/Mezzanin". This fixes the Karriere `mailto:%20office@...` link without touching the production database (the bad value is stored in `vacancies_introtext`); the stored text itself is unchanged and can be cleaned in the admin panel at any time.
- [x] `tel:` links of contact persons, the Unternehmensservice page and the admin mail are normalized; invalid JavaScript in the contact-person analytics handlers (unquoted values) and the stray "o" in the contact analytics label fixed.
- [x] E-mail templates (confirmation, solved, verification) use the central address.
- [x] Admin: the nine contact fields (phone, e-mail, address per section) were removed from the Filament pages "Kontakt" and "Seiten", replaced by a notice that they live in `config/company.php`; the stale defaults in the settings classes (`office@bontus-eybel.at`, old address spelling) are corrected. Anyone who edited these fields in the admin panel must now change `config/company.php` (a developer task) instead.
- [ ] Decisions: the fax number is still shown in the footer (the briefing says "only partly"); the Verwalterservice mailbox `verwalterservice@bereal-immobilien.at` is kept as is; the Impressum text still has the old data (WP7).

### WP4 URLs, redirects, sitemap
Briefing: "Über uns", "Leistung Baumanagement und Service", Technik #2 and #3.
Status 2026-10-28: implemented and tested locally (not committed, not deployed). Tests: `tests/Feature/UrlsRedirectsAndSitemapTest.php` (19 tests).
- [x] **Real HTTP 404:** the fallback route now answers 404 (it answered 200, which is why Google kept old addresses). A global handler renders every not-found error of a public page, including missing models (unknown competence, FAQ, job, property), with the same styled 404 page in German or English, with its own title, one H1 and `noindex`; admin, Livewire and JSON requests keep Laravel's default. The three buttons on the 404 page now link to the start pages. An unknown FAQ slug crashed with a 500 before; expired or future job ads answered 403 and now answer 404.
- [x] **301 redirects for old addresses:** `config/redirects.php` (path or path?query to new address, language prefix kept) applied by `RedirectLegacyUrls`, which runs right after the language prefix handling. Entries: `/ueber-uns` to `/` (the company story, "Why be real?" and "Our Story", is on the start page; decide if `/makler/ueber-uns` should be the target once it has content), the lorem-ipsum competence address and the lorem-ipsum service tab. Add anything that shows up as "not found" in Search Console here. `bontus-eybel.at` and the other hosts are server work (WP8).
- [x] **Readable addresses:** the competence "Bauverwaltung" had the placeholder slug `alias-qui-vel-ea-repellendus`; a data migration (`2026_10_28_205616_rename_lorem_ipsum_competence_slug`) renames it to `bauverwaltung` and the old address redirects. No lorem-ipsum slug exists for services in the database (`/service?tab=aliquam-repudiandae-expedita-rerum` is redirected to `sepa-mandat` in case old links exist). Open decision: the briefing says Baumanagement should point to be real Technik; that competence is currently filed under Hausverwaltung. Moving it (change "Unternehmensbereich" in the admin panel) automatically redirects `/leistung/bauverwaltung` to `/technik/leistung/bauverwaltung`.
- [x] **Each Leistung exists once:** `RedirectCompetenceToItsSection` sends `/leistung/x`, `/makler/leistung/x` and `/technik/leistung/x` permanently to the address of the section the competence belongs to (language prefix kept).
- [x] **Sitemap:** served dynamically at `/sitemap.xml` (`App\Services\SitemapBuilder`); the committed `public/sitemap.xml` is deleted and git-ignored; the public `GET /sitemap` that rewrote the file on every visit is gone. 129 unique addresses on the production data (was 99 with every Leistung three times): every page once, each competence once under its own section, `lastmod` on every address (latest change of the settings and models the page shows), English addresses with `hreflang` alternates for static pages and Leistungen (35), FAQ answers, service tabs, active job ads and properties added (German only), expired job ads and the empty `/makler/ueber-uns` left out. The host comes from the request, so production produces `https://www.bereal-immobilien.at/...`.
- [x] `/makler/ueber-uns` is `noindex` and not in the sitemap until it has content (WP9).
- [ ] **Deploy steps this adds:** run `php artisan migrate` (slug rename); delete the static `public/sitemap.xml` on the server, otherwise the old file keeps winning over the new route; refresh the route cache if it is used.
- [ ] **Server (WP8):** confirm the `www` redirect covers http and https and all hosts, `bontus-eybel.at`, `bereal-makler.at`, `bereal-technik.at`. Non-www already redirects with 301 (checked 2026-10-27).
- [ ] **Find more old addresses:** the production access logs would show which not-found addresses are really requested (read-only aggregation of paths, no IPs); not done because the log read was blocked. Search Console is the other source once it is set up (WP8).
- [x] **Stale tests removed (approved):** the 24 leftover starter-kit tests (`Auth/*`, `Settings/*`, `DashboardTest`, 8 files) tested routes that do not exist in this app and are deleted. Suite: 169 tests, all passing.

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
- [x] `robots.txt`: `Allow: /` kept, `Sitemap: https://www.bereal-immobilien.at/sitemap.xml` added, file now in the repo (`public/robots.txt`; production's copy must be replaced on deploy). Every page also has `<link rel="sitemap" type="application/xml" title="Sitemap" href=".../sitemap.xml">` in the head (`partials/head.blade.php`). Tests: `SitemapReferenceTest`. The sitemap content itself is still WP4.
- [~] `llms.txt`: served as `public/llms.txt` with placeholder text (German; built from the briefing facts and the current site; starts with an HTML comment marking it as a placeholder, remove it when Ronald's final text is in). Needs from Ronald: final wording, whether the English version is mentioned. Service links use today's slugs and must be updated when WP4 renames them (the test `LlmsTxtTest` fails on dead links, wrong address spelling, "Bontus" and missing company facts).
- [~] Server-rendered HTML check (all public pages fetched without JavaScript, 2026-10-28; production serves the same code and the same empty pages). Results:
  - [x] **Datenschutz was empty** (0 characters in `<main>`, title `bereal`): `Route::livewire('datenschutz', 'policy')` resolved to an empty scaffold (`components/⚡policy.blade.php`); the real markup sat in a dead Volt file (`livewire/policy.blade.php`, Volt is no longer installed). Fixed: class component `App\Livewire\Pages\Policy` with view `livewire/pages/policy.blade.php`, normal route, title/description/heading from `pages.policy.*` (no "Bontus", typo "Immobilienmanagment" fixed), both dead files removed. Now about 23,000 characters of policy text in the HTML. Tests: `PrivacyPolicyPageTest`. The policy text itself (`PagesSettings::dpgr_text`) is German only (WP10).
  - [ ] **Über uns (`/makler/ueber-uns`) is empty:** `livewire/makler/ueber.blade.php` is an empty scaffold. Needs the client's content (WP9); until then remove it from the sitemap or return 404/noindex.
  - [x] **Team and Contact content was loaded by JavaScript:** `<livewire:contact-departments lazy>` (Team and Contact) and `<livewire:contact-form lazy>` (Contact, includes the sidebar with address, phone and e-mail) only delivered a placeholder. This was the real reason why "the Team pages show no persons" (37 contact persons exist). Fixed by removing `lazy`: Team now serves about 4,000 and Contact about 5,900 characters, including persons, address and phone. Cost (unoptimized local render): Contact 320 to 590 ms, Team 160 to 580 ms, HTML 75 KB to 217 to 293 KB, because every person is a nested Livewire component with its own snapshot. WP8: render the person cards as plain Blade (or cache the block) to win the time back.
  - [x] **Company bug:** `company="$company"` was passed as a literal string and `ContactDepartments` declared the property as `$comapny` (typo), so the company was never applied and every section showed the Hausverwaltung departments. Fixed (`:company="$company"`, property renamed). Consequence of the correct data: only Hausverwaltung has departments (14); Makler has a single sub-department and Technik none, so the Makler and Technik team pages now show no persons. The briefing says to add the team or hide the page for now: decide (hide the pages/navigation until content exists, or deliver the persons). Tests: `ContactDepartmentsPagesTest`.
  - [ ] Related bug, not fixed: `ContactForm::$company` is `protected` and never set, so all 347 saved contact requests have an empty `company`; `Parts\References` accepts `company` but ignores it. Decide whether contact requests should record the section they were sent from (then also route the e-mail by section; the recipient is hard-coded to `office@bereal-immobilien.at`).
  - [x] **Service page (`/service`) was client-rendered:** the service list, descriptions, bullet lists and downloads were built by Alpine `x-for` (about 200 characters in the HTML). Now rendered by Blade: the menu consists of real `<a href="/service?tab=<slug>">` links, the selected service (description, bullet lists, downloads, contact form where configured) is in the HTML, and `wire:click` switches services through Livewire's `tab` URL property. Alpine only keeps the search filter (`x-show` on the already rendered items, same fuzzy matching with Fuse). Unknown `tab` falls back to the first service; the first service is canonical `/service`, every other service has a self-canonical `/service?tab=<slug>`. English falls back to German text per service where English is missing (as before). The navbar service dropdown got the same treatment (server-rendered links on every page, no more service descriptions in the Livewire snapshot of every page) and its filter placeholder is now translated (`service.filter_placeholder`). Tests: `ServicePageTest`. Verified in a real browser (headless Edge via DevTools, local copy): the search filter works on the Service page and in the navbar dropdown, on a fresh load, after switching a service (Livewire update) and after client-side navigation (`wire:navigate`), no console errors. **Pre-existing production bug fixed on the way:** the old filter never found anything on production (typing "mand" or "sepa" left 0 of 6 services), because Fuse was given `name` as a `{de, en}` object and cannot search objects; the new code passes plain strings in the current language (regression test `test_filter_receives_plain_service_names_it_can_search`). The same bug affected the navbar dropdown filter. Still open for later: readable slugs instead of lorem-ipsum ones (WP4), one `<h1>` and per-service titles (WP2), missing English texts for 3 services (WP10).
  - Fine (server-rendered, 900 to 14,000 characters in `<main>`): start pages, Leistungen, Karriere, FAQ list and detail, Impressum, Barrierefreiheit, Unternehmensservice, service detail pages, Immobiliensuche, property detail. Thin but present: Team intro (about 330 characters).
  - Still `bereal` as title (WP2): Unternehmensservice, FAQ detail, property detail.

### WP7 Impressum and Datenschutz
Briefing: "Impressum", "Datenschutz".
- [ ] Heading: replace "Angaben gemäß § 5 TMG" with Austrian basis (§ 5 ECG, § 14 UGB, § 25 MedienG).
- [ ] "Franz-Josefs-Kai 65/Mezzanin" everywhere; fix typo "Immobilienmanagment".
- [ ] Technik company: use `office@bereal-technik.at` (not `t.keinrath@bereal-immobilien.at`).
- [ ] Add managing directors and UID for Makler and Technik (data below). Gewerbe, Kammer, Aufsichtsbehörde, Firmenbuchgericht per company: client delivers.
- [x] Datenschutz page: title (no "Bontus"), text present in the HTML (see WP6). The legal text itself is checked by the client; cookie list `_gat` done in WP1.

### WP8 Domains, search engines, performance (previously "Christoph's")
Briefing: "Domains", Technik #9 and #10.
- [ ] `www.bereal-makler.at` to `/makler/start` and `www.bereal-technik.at` to `/technik/start` on `www.bereal-immobilien.at` (permanent redirect; Plesk or app-level host redirect). Needs server/DNS access beyond the current read-only access: decide how.
- [ ] Google Search Console and Bing Webmaster Tools: set up if missing, submit the sitemap, give the client read access.
- [ ] PageSpeed Insights for the start page and subpages, mobile first; shrink large images and videos (media is on S3).
- [x] Removed the stale 597 MB `storage/logs/laravel.log.old` on production (done manually by Markus).

### WP9 FAQ, texts and small content corrections in code or database
Briefing: "Startseite, Why be real?, Our Story", "Referenzen", "Bilder", "FAQ", "Team".
These sit in views, translations or the admin database. Technical part is ours, final wording comes from the client where marked.
- [ ] Texts: "seit fast 30 Jahren", explain "be" as Bachschwöller und Eybel, "Tanja Bachschwöller-Eybel" (Makler page: with ", BA"), remove "Bontus Eybel" everywhere (alt texts, subline, hashtags, domain).
- [ ] Alt texts: German descriptions, no "Bontus Eybel Intro Image", "bereal Makler Image", "animation", no underscores; logos get alt text.
- [ ] References: strip hashtag remnants (`##lovely #farbtupfer ...`), unify "Triester Straße". Includes cleanup of existing database content, to be done as a reviewed migration or admin task.
- [ ] FAQ: fix "European CommissionRat der Europäischen Union", the duplicated lowercase "kontaktieren sie uns", phrase the three non-question entries as questions; add a "Stand" date field. Outdated answers (Wertsicherung, Kurzzeitvermietung, Stromanbieter) need client input.
- [ ] Team pages: show no empty page. Hide until content exists, then render people.
- [ ] Corporate wording rules in UI texts: "be real" always lowercase, Sie-Form, gender "Mitarbeiter:innen" where space is short.

### WP10 English content completeness (audit of 2026-10-28, local copy of the production database)
Method: every public page rendered under `/en` and its visible text checked for German; all `*_de`/`*_en` settings pairs and all translatable models compared; `lang/*.json` and hard-coded strings scanned. Names, street names and legal terms (e.g. "ÖNORM") are not counted as gaps.

Complete (no action): the 144 `*_de`/`*_en` settings pairs of the translatable settings classes (`HausverwaltungLandingpage`, `MaklerLandingpage`, `TechnikLandingpage`, `VerwalterserviceLandingpage`, `Contact`, `Team`, `Vacancies`, `Faq`, `Leistungen`, `Search`) all have English text. Models fully translated: Competence, Department, Contactperson, Timeline, ServiceCard, Unternehmensservice keywords, References (titles are street names). `lang/en.json` has no empty or German values for the strings it contains.

Gaps, in order of visibility:
- [ ] **FAQ (largest gap):** 18 of 19 questions have no English `question`/`answer`; `/en/faq` and every `/en/faq/<slug>` shows German (85 German text blocks on the list page). Ids: 1,2,3,6,7,9,12,15,19,20,21,22,23,24,25,31,32,33. The one remaining German-content page text also needs English: "Sie haben Fragen oder Anregungen zu diesem Thema? / kontaktieren sie uns" (hard-coded, `livewire/pages/f-a-q.blade.php`). Needs translated content (client) or hiding the FAQ from the English site.
- [ ] **Cookie banner is German on every English page:** the package translates category and cookie descriptions once at boot (`__()` inside `registerCookies()`), before the request locale is known, so the English lang file is never used. Also the Google Maps cookie description is a hard-coded German string. Fix: register descriptions lazily or after the locale is set, and move the Maps text to the lang files.
- [ ] **`PagesSettings` has no English variants** (44 German-only properties) and is still read by public pages: Service page (`services_header`, `services_subheader`, `services_introtext`; the translatable `ServicesSettings` exists but is not used), contact-person section (`contactpersons_heading`, `contactpersons_introtext`), contact sidebar (`contactform_heading` "Sagen Sie uns Hallo!", per-company e-mail/phone/address), Impressum (`imprint_text`), Barrierefreiheit (`accessability_text`), Datenschutz (`dpgr_text`). Fix: add `_de`/`_en` pairs (and Filament fields) or switch the pages to the translatable classes. The legal pages need a decision whether an English version is wanted at all.
- [ ] **Services (Service page tabs):** 3 of 6 services have no English `name`/`description` (ids 6, 7, 12).
- [ ] **Job vacancies:** 4 of 7 have English identical to German in title, job title and description (ids 20, 21, 22, 23); the other 3 (inactive) were not checked. English text is needed.
- [ ] **Hard-coded German in views:** the contact-form confirmation ("Vielen Dank!", "Damit wir Ihre Anfrage bearbeiten können, ...", "Dazu haben wir Ihnen eine E-Mail mit einem Bestätigungslink gesendet.", `components/contact-form-sent.blade.php`, shown on 22 pages), the 404 page texts ("Diesen Inhalt konnten wir leider nicht finden", "Bitte prüfen Sie die Eingabe ..."), the service filter placeholder "Filtern Sie aus {n} Services" (`menu-service-dropdown.blade.php`, `hausverwaltung/service.blade.php`, on 44 pages), and the default meta description in `partials/head.blade.php`. These are `__()` keys or literals with no entry in `lang/en.json`, so they fall back to German. WP2 covers the meta description.
- [ ] **E-mails are German only:** subjects in `ContactRequestConfirmedMail`, `ContactRequestSolvedMail`, `VerificationEmail` and the e-mail templates. Someone who submits the English contact form receives German mails. Needs a locale on the contact request (store the locale, send mails with it).
- [ ] **Imported listings** (`/en/makler/immobilie/...`, 29 German blocks per property) come from the Justimmo API in German. Decide: leave German, hide from the English site, or translate.
- [ ] **Brand leftovers in English content:** "Bontus Eybel" in `cta_image_alt_en` (Makler), `about_image_alt_en` (Technik) and one Timeline description (also in German; see WP9).
- [ ] **Privacy policy text is missing from the HTML** on `/en/datenschutz` (and `/datenschutz`): the page renders only the layout (see WP6/WP7), so there is currently no English or German policy text served to crawlers.
- [ ] **Missing keys in `lang/en.json`:** 106 keys exist in `de.json` but not in `en.json`; 87 of them are starter-kit auth/settings/error strings (the 17 stale tests' area), so not public. The 6 public ones are the contact-form and 404 strings above. `lang/en/*.php` does not exist (framework defaults cover validation and pagination).
- Guard: add an English-completeness test next to `TranslationKeysTest` (every used `__()` key present in `en.json`, translatable models and settings have English) once the content gaps are closed, otherwise it would fail today.

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
- Domain redirects (`bereal-makler.at`, `bereal-technik.at`) and production writes: how do we get write access, and who deploys? Production is not deployed from git today.
- Production PHP is still 8.3; switching to 8.5 in Plesk is a separate ops step (check extensions `intl`, `exif`, `zip`, `bcmath`, `pdo_sqlite`, php.ini, rollback plan).
- Local images load from the production S3 bucket via public URLs only (dummy keys). Admin login at `/admin` is e-mail and password plus a required authenticator app (two-factor); it works locally once a local admin user exists (`herd php artisan make:filament-user`, then set `admin` to true). The LinkedIn settings are only for the LinkedIn wall and the profile URL, not for logging in.

### Deliverables needed from Ronald / the client
Not ours, but they block or finish parts above. This list is the basis for the "what is missing" report; add items as they come up.

| Deliverable | Needed for | Status of what is in the repo |
|---|---|---|
| **Share images**, one per section (be real Immobilien, Makler, Technik): 1200 x 630 px, PNG or JPG, replace `public/og-image.png`, `og-makler.png`, `og-technik.png` | WP2 (link previews on WhatsApp, LinkedIn, Facebook, Google) | **Placeholder** (white logo on section colour, generated by us) |
| Final `llms.txt` text (group, companies, services, contact, key links; decide whether the English version is mentioned) | WP6 | Placeholder text, marked with a comment in the file |
| New logo files for Immobilien, Verwaltung, Makler, Technik (RGB and CMYK), social media profile URLs (`sameAs`) | WP5, social profiles | Current logos in `public/logos` |
| Legal details per company: Gewerbe, Kammer, Aufsichtsbehörde, Firmenbuchgericht; review of the Datenschutz text (German, and English if wanted) | WP7 | Data in section 4 only |
| English texts: 18 of 19 FAQ questions and answers, 3 services, 4 job ads (and the Datenschutz/Impressum/Barrierefreiheit pages if an English version is wanted) | WP10 | English falls back to German |
| FAQ updates (Wertsicherung, Kurzzeitvermietung, Stromanbieter) and "Stand" dates | WP9 | Outdated texts still online |
| Team content for Makler and Technik (persons, departments), content for `/makler/ueber-uns`; decision whether to hide these pages until then | WP6, WP9 | Pages exist, show no persons or no content |
| Decisions: expose the language switcher publicly, domain redirects/access for `bereal-makler.at` and `bereal-technik.at`, Search Console/Bing access | WP1, WP8 | Open |
