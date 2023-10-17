<?php

namespace App\Livewire\Admin\Accounts\Role;

use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Role extends Component
{

    #[Title('utilisateurs|role')]
    public function render()
    {
        return view('livewire.admin.accounts.role.role');
    }
}
