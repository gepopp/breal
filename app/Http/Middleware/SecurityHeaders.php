<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // PHP announces its version in X-Powered-By (expose_php); nothing needs it.
        header_remove('X-Powered-By');
        $response->headers->remove('X-Powered-By');

        foreach (config('security.headers') as $name => $value) {
            $response->headers->set($name, $value);
        }

        if ($this->sendsHsts($request)) {
            $response->headers->set('Strict-Transport-Security', 'max-age='.config('security.hsts.max_age'));
        }

        if ($policy = $this->contentSecurityPolicy()) {
            $response->headers->set($policy['header'], $policy['value']);

            if ($reportPath = config('security.csp.report_path')) {
                $response->headers->set('Reporting-Endpoints', config('security.csp.report_group').'="'.url($reportPath).'"');
            }
        }

        return $response;
    }

    private function sendsHsts(Request $request): bool
    {
        return $request->isSecure()
            && config('security.hsts.max_age') > 0
            && app()->environment(config('security.hsts.environments'));
    }

    /**
     * @return array{header: string, value: string}|null
     */
    private function contentSecurityPolicy(): ?array
    {
        $mode = config('security.csp.mode');

        if (! in_array($mode, ['report-only', 'enforce'], true)) {
            return null;
        }

        $value = collect(config('security.csp.directives'))
            ->map(fn (array $sources, string $directive): string => $directive.' '.implode(' ', $sources))
            ->implode('; ');

        // report-uri for Firefox and Safari, report-to (see the Reporting-Endpoints header) for Chromium.
        if ($reportPath = config('security.csp.report_path')) {
            $value .= '; report-uri '.$reportPath.'; report-to '.config('security.csp.report_group');
        }

        return [
            'header' => $mode === 'enforce' ? 'Content-Security-Policy' : 'Content-Security-Policy-Report-Only',
            'value' => $value,
        ];
    }
}
