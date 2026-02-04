<?php

namespace App\Livewire\Makler\Parts;

use App\Models\Realty;
use App\Settings\MaklerLandingpageSettings;
use App\Traits\SplitsHtmlText;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Hero extends Component
{
    use SplitsHtmlText;

    public string $text = '';

    public array $arten = [];

    public array $typen = [];

    public function mount(MaklerLandingpageSettings $settings)
    {
        $this->text = $settings->intro_description;
        $this->arten = DB::table('realties')->select('nutzungsart')->distinct()->get()->toArray();
        $this->typen = DB::table('realties')->select('vermarktungsart')->distinct()->get()->toArray();
    }

    public function render(MaklerLandingpageSettings $settings)
    {
        $images = Media::whereIn('id', $settings->hero_images)->get();
        $realEstates = Realty::all();
        $preparedText = $this->prepareText();

        return view('livewire.makler.parts.hero', compact('settings', 'images', 'realEstates', 'preparedText'));
    }
}
