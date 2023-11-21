<?php

namespace App\Livewire\Auth;

use App\Enums\Provider;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
class LoginPage extends Component
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
        }
    }

    #[Title('page de connexion')]
    public function render()
    {
        return view('livewire.auth.login-page');
    }
}
