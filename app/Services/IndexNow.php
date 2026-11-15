<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

/**
 * Client of the IndexNow protocol (https://www.indexnow.org/documentation): one request tells the participating
 * search engines about up to 10,000 addresses.
 */
class IndexNow
{
    private const MAX_URLS_PER_REQUEST = 10000;

    /**
     * Sends the addresses that belong to this site; others are dropped, because IndexNow rejects them.
     *
     * @param  list<string>  $urls
     * @return int number of addresses sent
     *
     * @throws RequestException when a request is refused or fails
     */
    public function submit(array $urls): int
    {
        $host = (string) parse_url((string) config('app.url'), PHP_URL_HOST);
        $key = (string) config('indexnow.key');

        $urls = collect($urls)
            ->filter(fn (string $url): bool => parse_url($url, PHP_URL_HOST) === $host)
            ->unique()
            ->values();

        foreach ($urls->chunk(self::MAX_URLS_PER_REQUEST) as $chunk) {
            Http::timeout(10)
                ->acceptJson()
                ->post((string) config('indexnow.endpoint'), [
                    'host' => $host,
                    'key' => $key,
                    'keyLocation' => rtrim((string) config('app.url'), '/').'/'.$key.'.txt',
                    'urlList' => $chunk->all(),
                ])
                ->throw();
        }

        return $urls->count();
    }
}
