#!/usr/bin/env node
/**
 * Runs Lighthouse (mobile, like PageSpeed Insights) over the key pages and prints a score table.
 *
 *   node scripts/lighthouse.mjs                       all key pages of production
 *   node scripts/lighthouse.mjs --base=https://x.test pages of another host
 *   node scripts/lighthouse.mjs --only=makler,technik only URLs containing one of the words
 *   node scripts/lighthouse.mjs --desktop             desktop preset instead of mobile
 *   node scripts/lighthouse.mjs --out=./lh-reports    keep the HTML reports (default: temp folder, deleted)
 *
 * Needs Node and Chrome (CHROME_PATH is looked up on Windows/macOS/Linux). Lighthouse itself is fetched by npx,
 * nothing is added to the project. Scores come from this machine and network: compare runs from the same
 * machine with each other, not with PageSpeed Insights.
 */
import { spawnSync } from 'node:child_process';
import { existsSync, mkdirSync, mkdtempSync, readFileSync, rmSync, writeFileSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join } from 'node:path';

const args = Object.fromEntries(process.argv.slice(2).map((a) => {
    const [key, value = 'true'] = a.replace(/^--/, '').split('=');
    return [key, value];
}));

const base = (args.base ?? 'https://www.bereal-immobilien.at').replace(/\/$/, '');
const preset = args.desktop ? 'desktop' : 'mobile';
const only = args.only ? args.only.split(',') : null;

const paths = [
    '/', '/makler', '/technik',
    '/leistungen', '/service', '/team', '/faq', '/karriere', '/kontakt',
    '/makler/ueber-uns', '/makler/immobiliensuche', '/makler/team', '/makler/kontakt',
    '/technik/leistungen', '/technik/team', '/technik/kontakt',
    '/leistung/allgemeine-beratung', '/technik/leistung/energieausweis',
    '/impressum', '/datenschutz', '/barrierefreiheit',
    '/en',
];

const urls = paths
    .filter((p) => !only || only.some((word) => p.includes(word)))
    .map((p) => base + (p === '/' ? '' : p));

const chromeCandidates = [
    process.env.CHROME_PATH,
    'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
    'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
    '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
    '/usr/bin/google-chrome',
    '/usr/bin/chromium',
].filter(Boolean);
const chrome = chromeCandidates.find((path) => existsSync(path));
if (!chrome) {
    console.error('Chrome not found, set CHROME_PATH.');
    process.exit(1);
}

const keep = Boolean(args.out);
const outDir = args.out ?? mkdtempSync(join(tmpdir(), 'lighthouse-'));
mkdirSync(outDir, { recursive: true });

const rows = [];
for (const [index, url] of urls.entries()) {
    const name = url.replace(/^https?:\/\//, '').replace(/[^a-z0-9]+/gi, '_');
    const target = join(outDir, name);
    process.stderr.write(`[${index + 1}/${urls.length}] ${url}\n`);

    const run = spawnSync('npx', [
        '--yes', 'lighthouse@12', url,
        ...(preset === 'desktop' ? ['--preset=desktop'] : []),
        '--output=json', '--output=html', `--output-path=${target}`,
        '--quiet', `--chrome-flags=--headless=new --no-sandbox`,
    ], { env: { ...process.env, CHROME_PATH: chrome }, shell: true, encoding: 'utf8' });

    const jsonFile = `${target}.report.json`;
    if (run.status !== 0 || !existsSync(jsonFile)) {
        rows.push({ url, error: (run.stderr || 'failed').trim().split('\n').pop() });
        continue;
    }

    const report = JSON.parse(readFileSync(jsonFile, 'utf8'));
    const score = (id) => Math.round((report.categories[id]?.score ?? 0) * 100);
    const audit = (id) => report.audits[id]?.displayValue ?? '-';
    rows.push({
        url,
        performance: score('performance'),
        accessibility: score('accessibility'),
        bestPractices: score('best-practices'),
        seo: score('seo'),
        lcp: audit('largest-contentful-paint'),
        cls: audit('cumulative-layout-shift'),
        tbt: audit('total-blocking-time'),
    });
}

const pad = (value, width) => String(value).padEnd(width);
const path = (url) => url.replace(base, '') || '/';
const widest = Math.max(...rows.map((r) => path(r.url).length), 4);

console.log(`\nLighthouse ${preset}, ${base}, ${new Date().toISOString().slice(0, 16).replace('T', ' ')}\n`);
console.log(`${pad('Page', widest)}  Perf  A11y  BP   SEO  LCP      CLS    TBT`);
for (const row of rows) {
    if (row.error) {
        console.log(`${pad(path(row.url), widest)}  ERROR ${row.error}`);
        continue;
    }
    console.log(`${pad(path(row.url), widest)}  ${pad(row.performance, 4)}  ${pad(row.accessibility, 4)}  ${pad(row.bestPractices, 3)}  ${pad(row.seo, 3)}  ${pad(row.lcp.replace(/\s/g, ''), 7)}  ${pad(row.cls, 5)}  ${row.tbt.replace(/\s/g, '')}`);
}

const ok = rows.filter((r) => !r.error);
if (ok.length) {
    const avg = (key) => Math.round(ok.reduce((sum, r) => sum + r[key], 0) / ok.length);
    console.log(`${pad('Average', widest)}  ${pad(avg('performance'), 4)}  ${pad(avg('accessibility'), 4)}  ${pad(avg('bestPractices'), 3)}  ${avg('seo')}`);
}

if (keep) {
    writeFileSync(join(outDir, 'summary.json'), JSON.stringify(rows, null, 2));
    console.log(`\nReports in ${outDir}`);
} else {
    rmSync(outDir, { recursive: true, force: true });
}
