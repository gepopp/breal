<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Response headers
    |--------------------------------------------------------------------------
    |
    | Sent by App\Http\Middleware\SecurityHeaders on every response.
    |
    */

    'headers' => [
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'SAMEORIGIN',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'camera=(), microphone=(), payment=(), usb=(), geolocation=(self)',
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP Strict Transport Security
    |--------------------------------------------------------------------------
    |
    | Only sent for HTTPS requests in the listed environments, so staging and
    | local never pin a browser to HTTPS. includeSubDomains and preload are left
    | out on purpose until every subdomain and company domain was checked.
    |
    */

    'hsts' => [
        'max_age' => (int) env('SECURITY_HSTS_MAX_AGE', 31536000),
        'environments' => ['production'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy
    |--------------------------------------------------------------------------
    |
    | "report-only" only logs violations in the browser console, "enforce"
    | blocks them and "off" sends no policy. Alpine and Livewire need inline
    | and eval scripts, so the script policy is a first step, not a lockdown.
    |
    | TODO: every host listed below needs a review (why is it used, is it still
    | needed, is it named in the privacy statement?), see the section "Third-party
    | hosts to review" in AUDIT-2026-10-31.md.
    |
    */

    'csp' => [
        'mode' => env('SECURITY_CSP_MODE', 'report-only'),

        'directives' => [
            'default-src' => ["'self'"],
            'script-src' => [
                "'self'", "'unsafe-inline'", "'unsafe-eval'",
                'https://cdn.jsdelivr.net', 'https://unpkg.com', 'https://rawcdn.githack.com',
                'https://www.googletagmanager.com', 'https://maps.googleapis.com',
                'https://player.vimeo.com', 'https://use.typekit.net',
            ],
            'style-src' => [
                "'self'", "'unsafe-inline'",
                'https://cdn.jsdelivr.net', 'https://use.typekit.net', 'https://p.typekit.net', 'https://fonts.googleapis.com',
            ],
            'font-src' => ["'self'", 'data:', 'https://use.typekit.net', 'https://fonts.gstatic.com'],
            'img-src' => ["'self'", 'data:', 'blob:', 'https:'],
            'media-src' => ["'self'", 'blob:', 'https:'],
            'frame-src' => ["'self'", 'https://player.vimeo.com', 'https://www.google.com'],
            'connect-src' => [
                "'self'", 'https:', 'wss:',
            ],
            'object-src' => ["'none'"],
            'base-uri' => ["'self'"],
            'form-action' => ["'self'"],
            'frame-ancestors' => ["'self'"],
        ],
    ],

];
