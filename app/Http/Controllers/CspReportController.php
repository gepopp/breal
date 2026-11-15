<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Collects Content-Security-Policy violation reports of the browsers in the "csp" log channel.
 *
 * Understands both formats: the legacy report-uri post (application/csp-report) and the Reporting API list
 * (application/reports+json). Reports caused by browser extensions are dropped, every distinct violation is
 * logged once per hour so a busy page cannot flood the log.
 */
class CspReportController extends Controller
{
    private const MAX_BODY_BYTES = 20000;

    private const MAX_REPORTS = 10;

    private const EXTENSION_SCHEMES = ['chrome-extension', 'moz-extension', 'safari-extension', 'safari-web-extension', 'ms-browser-extension'];

    public function __invoke(Request $request): Response
    {
        $body = $request->getContent();

        if (strlen($body) <= self::MAX_BODY_BYTES) {
            foreach (array_slice($this->reports($body), 0, self::MAX_REPORTS) as $report) {
                $this->log($report);
            }
        }

        return response()->noContent();
    }

    /**
     * @return list<array{directive: string, blocked: string, document: string, source: string, line: int, disposition: string}>
     */
    private function reports(string $body): array
    {
        $data = json_decode($body, true);

        if (! is_array($data)) {
            return [];
        }

        if (isset($data['csp-report']) && is_array($data['csp-report'])) {
            $r = $data['csp-report'];

            return [$this->report(
                $r['effective-directive'] ?? $r['violated-directive'] ?? null,
                $r['blocked-uri'] ?? null,
                $r['document-uri'] ?? null,
                $r['source-file'] ?? null,
                $r['line-number'] ?? null,
                $r['disposition'] ?? null,
            )];
        }

        $reports = [];

        foreach (array_is_list($data) ? $data : [] as $entry) {
            if (is_array($entry) && ($entry['type'] ?? null) === 'csp-violation' && is_array($entry['body'] ?? null)) {
                $b = $entry['body'];

                $reports[] = $this->report(
                    $b['effectiveDirective'] ?? $b['violatedDirective'] ?? null,
                    $b['blockedURL'] ?? null,
                    $b['documentURL'] ?? null,
                    $b['sourceFile'] ?? null,
                    $b['lineNumber'] ?? null,
                    $b['disposition'] ?? null,
                );
            }
        }

        return $reports;
    }

    /**
     * @return array{directive: string, blocked: string, document: string, source: string, line: int, disposition: string}
     */
    private function report(mixed $directive, mixed $blocked, mixed $document, mixed $source, mixed $line, mixed $disposition): array
    {
        $text = fn (mixed $value): string => is_scalar($value) ? Str::limit(strip_tags((string) $value), 300, '') : '';

        return [
            'directive' => $text($directive),
            'blocked' => $text($blocked),
            'document' => $text($document),
            'source' => $text($source),
            'line' => is_numeric($line) ? (int) $line : 0,
            'disposition' => $text($disposition),
        ];
    }

    /**
     * @param  array{directive: string, blocked: string, document: string, source: string, line: int, disposition: string}  $report
     */
    private function log(array $report): void
    {
        if ($report['directive'] === '' || $this->causedByExtension($report)) {
            return;
        }

        $key = 'csp-report:'.sha1(implode('|', [
            $report['directive'],
            $report['blocked'],
            parse_url($report['document'], PHP_URL_PATH),
        ]));

        if (Cache::add($key, true, now()->addHour())) {
            Log::channel('csp')->info('CSP violation', $report);
        }
    }

    /**
     * @param  array{directive: string, blocked: string, document: string, source: string, line: int, disposition: string}  $report
     */
    private function causedByExtension(array $report): bool
    {
        foreach ([$report['blocked'], $report['source']] as $uri) {
            $scheme = strtolower((string) parse_url($uri, PHP_URL_SCHEME));

            if (in_array($scheme, self::EXTENSION_SCHEMES, true)) {
                return true;
            }
        }

        return false;
    }
}
