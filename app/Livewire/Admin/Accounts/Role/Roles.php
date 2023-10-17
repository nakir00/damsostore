<?php

namespace App\Livewire\Admin\Accounts\Role;

use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.accounts')]
class Roles extends Component
{
    
    #[Title('Roles utilisateurs')]
    public function render()
    {
        return view('livewire.admin.accounts.role.roles');
    }
}
