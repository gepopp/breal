<?php

namespace App\Http\Controllers;

use App\Models\Realty;
use Illuminate\Http\RedirectResponse;

/**
 * "/objekt/<number>" was the property address of the old website (the number is the Justimmo object number).
 * A property that is still in the feed goes to its page (301), every other number answers 410 like a property
 * that left the feed (see GonePages).
 */
class LegacyObjectController extends Controller
{
    public function __invoke(string $number): RedirectResponse
    {
        $realty = Realty::where('objektnr_intern', $number)->firstOrFail();

        return redirect()->route('makler.immobilie', $realty, 301);
    }
}
