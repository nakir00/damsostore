<?php

namespace App\Livewire\Admin;


use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{


    #[Title('Tableau de bord')]
    public function render()
    {
        return view('livewire.admin.dashboard');
    }

}
