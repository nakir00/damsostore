<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.client')]
class Dashboard extends Component
{
    public function mount(): void
    {
        if(Auth::check())
        {

            if(auth()->user()->role==='admin')
            {
                $this->redirect(
                    route('filament.admin.pages.dashboard'),
                    //session('url.intended', RouteServiceProvider::HOME),
                    navigate: true
                );
            }elseif(auth()->user()->role==='client'){
                $this->redirect(
                    route('client.dashboard'),
                    //session('url.intended', RouteServiceProvider::HOME),
                    navigate: true
                );
            }
        }else{
            $this->redirect(
                route('auth.login'),
                //session('url.intended', RouteServiceProvider::HOME),
                navigate: true
            );
        }
    }

    #[Title('Tableau de bord')]
    public function render()
    {
        return view('livewire.admin.dashboard');
    }

}
