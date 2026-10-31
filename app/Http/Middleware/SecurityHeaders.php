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

        foreach (config('security.headers') as $name => $value) {
            $response->headers->set($name, $value);
        }

        if ($this->sendsHsts($request)) {
            $response->headers->set('Strict-Transport-Security', 'max-age='.config('security.hsts.max_age'));
        }

        if ($policy = $this->contentSecurityPolicy()) {
            $response->headers->set($policy['header'], $policy['value']);
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

        return [
            'header' => $mode === 'enforce' ? 'Content-Security-Policy' : 'Content-Security-Policy-Report-Only',
            'value' => $value,
        ];
    }
}
