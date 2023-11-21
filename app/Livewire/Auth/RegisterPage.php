<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
class RegisterPage extends Component
{

    public function mount(): void
    {
        if(auth()->check())
        {
            if(auth()->user()->role==='client'){
                $this->redirect(
                    route('client.dashboard'),
                    //session('url.intended', RouteServiceProvider::HOME),
                    navigate: true
                );
            }elseif(auth()->user()->role==='admin')
            {
                $this->redirect(
                    route('filament.admin.pages.dashboard'),
                    //session('url.intended', RouteServiceProvider::HOME),
                    navigate: true
                );
            }
        }
    }

    #[Title('page de connexion')]
    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
