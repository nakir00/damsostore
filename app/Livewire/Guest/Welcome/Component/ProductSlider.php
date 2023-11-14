<?php

namespace App\Livewire\Guest\Welcome\Component;

use Livewire\Component;

class ProductSlider extends Component
{
    public $collection;

    public function render()
    {
        return view('livewire.guest.welcome.component.product-slider');
    }
}
