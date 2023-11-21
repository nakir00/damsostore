<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class RegisterForm extends Component implements HasForms
{

    use InteractsWithForms;

    public ?array $data = [];

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
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nom complet')
                    ->required()
                    ->maxLength(255)
                    ->autofocus(),
                TextInput::make('email')
                    ->label('Adresse e-mail')
                    ->email()
                    ->required()
                    ->unique(table: User::class)
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('Mot de Passe')
                    ->password()
                    ->required()
                    ->rule(Password::default())
                    ->same('passwordConfirmation')
                    ->validationAttribute(__('filament-panels::pages/auth/register.form.password.validation_attribute')),
                TextInput::make('passwordConfirmation')
                    ->label('Confirmer')
                    ->password()
                    ->required()
                    ->dehydrated(false),
            ])
            ->statePath('data');
    }

    public function register(): void
    {

        $data = $this->form->getState();

       // $data['password']=Hash::make($data['password']);
        $user = User::create($data);

        event(new Registered($user));

        auth()->login($user);

        session()->regenerate();

        $this->redirect(route('client.dashboard'));
    }

    public function render()
    {
        return view('livewire.auth.register-form');
    }
}
