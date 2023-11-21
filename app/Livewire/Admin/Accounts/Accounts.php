<?php

namespace App\Livewire\Admin\Accounts;

use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.client')]
class Accounts extends Component
{



    #[Title('Mettre à jour l\'utilisateur')]
    public function render()
    {
        
        return view('livewire.admin.accounts.accounts');
    }
}
