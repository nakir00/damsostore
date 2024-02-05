<?php

namespace App\Livewire\Auth;

use App\Models\RegisterToken;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Auth\Events\Registered;
use Livewire\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Date;
use Illuminate\Validation\Rules\Password;

class RegisterAdminForm extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public ?RegisterToken $registerData;

    public function mount($registerData)
    {
        $this->registerData=$registerData;
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('email')
                    ->label('email')
                    ->default($this->registerData->email)
                    ->disabled(),
                TextInput::make('name')
                    ->label('nom complet')
                    ->required()
                    ->autofocus(),
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
                TextInput::make('role')
                    ->label('role')
                    ->default($this->registerData->role)
                    ->disabled(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {

        unset($this->data['passwordConfirmation']);

        $user = User::create($this->data);

        event(new Registered($user));

        auth()->login($user);

        session()->regenerate();

        $this->registerData->user_id=$user->id;

        $this->registerData->consumed_at=Date::now();

        $this->registerData->save();

        $this->redirect(route('filament.admin.pages.dashboard'));
    }

    public function render(): View
    {
        return view('livewire.auth..register-admin-form');
    }
}
