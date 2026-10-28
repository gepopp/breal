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
- 17 stale starter-kit tests: repair or remove (needs approval per project rules)?
- Domain redirects (`bereal-makler.at`, `bereal-technik.at`) and production writes: how do we get write access, and who deploys? Production is not deployed from git today.
- Production PHP is still 8.3; switching to 8.5 in Plesk is a separate ops step (check extensions `intl`, `exif`, `zip`, `bcmath`, `pdo_sqlite`, php.ini, rollback plan).
- Local images load from the production S3 bucket via public URLs only (dummy keys). Admin login is LinkedIn OAuth and does not work locally.

Inputs from the client / Ronald (not ours, but they block parts above): legal details per company (Gewerbe, Kammer, Aufsichtsbehörde, Firmenbuchgericht), Datenschutz text review, `llms.txt` text, FAQ updates, team content and Über-uns content, social profile URLs, new logo files.
