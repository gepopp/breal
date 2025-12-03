<?php

namespace App\View\Components;

use App\Settings\PagesSettings;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ContactFormSidebar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public PagesSettings $pagesSettings)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.contact-form-sidebar');
    }
}
