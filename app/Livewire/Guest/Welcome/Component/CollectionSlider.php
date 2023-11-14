<?php

namespace App\Livewire\Guest\Welcome\Component;

use Livewire\Component;

class CollectionSlider extends Component
{
    public $collections;

    public function render()
    {
        return view('livewire.guest.welcome.component.collection-slider');
    }
}
