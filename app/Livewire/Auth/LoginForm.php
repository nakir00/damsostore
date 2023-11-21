<?php

namespace App\Livewire\Auth;

use App\Enums\Provider;
use App\Providers\RouteServiceProvider;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class LoginForm extends Component implements HasForms
{

    use WithRateLimiting;
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $status = Password::sendResetLink(
            $this->only('email')
        );

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
                TextInput::make('email')
                    ->label('identifiant')
                    ->email()
                    ->required()
                    ->autocomplete()
                    ->autofocus(),
                TextInput::make('password')
                ->hint(new HtmlString("<a href='forgot-password'>mot de passe oublié ?</a>"))
                    ->label('mot de passe')
                    //->hint(filament()->hasPasswordReset() ? new HtmlString(Blade::render('<x-filament::link :href="filament()->getRequestPasswordResetUrl()"> {{ __(\'filament-panels::pages/auth/login.actions.request_password_reset.label\') }}</x-filament::link>')) : null)
                    ->password()
                    ->required(),
                Checkbox::make('remember')
                    ->label('se souvenir de moi')
            ])
            ->statePath('data');
    }


    public function create(): void
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/login.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/login.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();
            return;
        }

        $data = $this->form->getState();
           // dd($data['email'],$data['password'],$data['remember']);
        if (!auth()->attempt(['email'=>$data['email'],'password'=>$data['password']],$data['remember'] )) {
            throw ValidationException::withMessages([
                'data.email' => 'identifiant ou mot de passe incorrecte',
            ]);
        }

        session()->regenerate();

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

    public function render()
    {
        return view('livewire.auth.login-form')->with([
            'providers'=> Provider::values()
        ]);
    }
}
