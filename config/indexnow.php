<?php

/*
|--------------------------------------------------------------------------
| IndexNow
|--------------------------------------------------------------------------
|
| Tells Bing (and the other IndexNow search engines, not Google) right away when a page was added, changed or removed
| in the CMS. The key is public by design: the file public/<key>.txt with the key as its content proves that the site
| is ours (IndexNowTest checks that the two match). Only production reports by default.
|
*/

return [

    'enabled' => (bool) env('INDEXNOW_ENABLED', env('APP_ENV') === 'production'),

    'key' => env('INDEXNOW_KEY', '54d869ad64d8ed000c507c83cdc96d66'),

    'endpoint' => env('INDEXNOW_ENDPOINT', 'https://api.indexnow.org/indexnow'),

    /*
     | Changes made within this many seconds are sent together in one request.
     */
    'delay' => (int) env('INDEXNOW_DELAY', 60),

];
