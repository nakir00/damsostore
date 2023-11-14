<?php

namespace App\Livewire\Guest\Collection\Component;

use App\Models\Collection;
use Livewire\Component;

class CollectionSlider extends Component
{

    public $collections;

    public function mount($collections)
    {
        $this->collections=$collections;
    }

    public function render()
    {
        return view('livewire.guest.collection.component.collection-slider');
    }
}
