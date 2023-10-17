<?php

namespace App\Livewire\Admin\Parametres\Marque;

use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.parametres')]
class Marques extends Component
{

    #[Title('Marques de produits')]
    public function render()
    {
        return view('livewire.admin.parametres.marque.marques');
    }
}
