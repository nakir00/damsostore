<?php

namespace App\Livewire\Guest\Product\Component;

use Livewire\Component;

class Images extends Component
{
    public $images;

    public function mount($images)
    {
        $this->images=$images;
    }

    public function render()
    {
        return view('livewire.guest.product.component.images');
    }
}
