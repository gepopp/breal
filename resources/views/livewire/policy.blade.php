<?php

use function Livewire\Volt\{state, layout, title};

layout('components.site');
title('Bontus Eybel Immobilien - Datenschutzerklärung');

state();

?>
<x-section>
    <div >
        <span ></span>
        <div >
            <div>
                <div>
                    <x-headings level="1">
                        <x-slot name="tag">secure</x-slot>
                        Datenschutzerklärung der be real Immobilienmanagment GmbH
                    </x-headings>
                </div>
            </div>
            <div >
                <div class="prose">
                    {!! app(App\Settings\PagesSettings::class)->dpgr_text !!}
                </div>
            </div>
        </div>
    </div>
</x-section>
