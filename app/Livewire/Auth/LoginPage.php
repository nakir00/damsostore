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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

use function Pest\Laravel\get;

#[Layout('layouts.auth')]
class LoginPage extends Component
{
    public $modalHeader="";
    public $modalContent="";

    public function mount(): void
    {
        if(Auth::check())
        {
            if(auth()->user()->role==='admin')
            {
                $this->redirect(
                    route('filament.admin.pages.dashboard'),
                );
            }elseif(auth()->user()->role==='client'){
                $this->redirect(
                    route('client.dashboard'),
                    //session('url.intended', RouteServiceProvider::HOME),
                    navigate: true
                );
            }
        }
        if(array_key_exists('status',Session::all()))
        {
            if(Session::get('status')==='expired')
            {
                $this->modalHeader="Ce lien a expiré";
                $this->modalContent="Votre lien a expiré.";
            }
            if(Session::get('status')==='consumed')
            {
                $this->modalHeader="déjà utilisé";
                $this->modalContent="votre lien a déjà été utilisé.";
            }
        }

    }


    #[Title('page de connexion')]
    public function render()
    {
        return view('livewire.auth.login-page')->with([
            'header'=>$this->modalHeader,
            'content'=>$this->modalContent
        ]);
    }
}
