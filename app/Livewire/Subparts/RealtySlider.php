<?php

namespace App\Livewire\Subparts;

use App\Models\Realty;
use Livewire\Attributes\Computed;
use Livewire\Component;

class RealtySlider extends Component
{
    public Realty $realty;

    /**
     * The photos with the caption the feed gives them, if any.
     *
     * @return list<array{url: string, title: ?string}>
     */
    #[Computed]
    public function realtyImages(): array
    {
        $files = $this->realty->data['anhaenge']['anhang'];
        $images = array_filter($files, fn ($file) => in_array($file['format'], ['jpeg', 'png', 'jpg']));
        $photos = [];

        foreach ($images as $image) {
            $title = $image['anhangtitel'] ?? null;

            $photos[] = [
                'url' => $image['daten']['pfad'],
                'title' => is_string($title) && trim($title) !== '' ? trim($title) : null,
            ];
        }

        return $photos;
    }

    public function render()
    {
        return view('livewire.subparts.realty-slider');
    }
}
