<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')]
class extends Component
{
    #[Rule(['required', 'string', 'email'])]
    public string $email = '';

    public function sendPasswordResetLink(): void
    {
        $this->validate();

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', 'Nous ne trouvons pas d’utilisateur avec cette adresse e-mail.');

            return;
        }

        $this->reset('email');

        session()->flash('status', 'le lien de réinitialisation de votre mot de passe a été envoyé.');
    }
}; ?>

<div class=" bg-green-100  w-full h-screen flex justify-center items-center" >



    <div class="flex flex-col justify-center px-6 py-8 lg:px-8 w-96 rounded-md bg-white drop-shadow-2xl">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm flex flex-col items-center">
          <x-application-logo class="mx-auto w-auto fill-current h-9" />
          <h2 class="mt-2 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Mot de passe oublié</h2>
        </div>

        <div class="mt-4 w-10 sm:mx-auto sm:w-full sm:max-w-sm">
                        {{--  <livewire:auth.loginForm /> --}}
                        <div>
                            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                Mot de passe oublié ? Aucun problème. <br> Indiquez-nous simplement votre adresse e-mail et nous vous enverrons par e-mail un lien de réinitialisation de mot de passe qui vous permettra d'en choisir un nouveau.
                            </div>

                            <!-- Session Status -->
                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <form wire:submit="sendPasswordResetLink">
                                <!-- Email Address -->
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input wire:model="email" id="email" class="block border-black ring-black mt-1 w-full" type="email" name="email" required autofocus />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div class="flex items-center justify-end mt-4">
                                    <x-primary-button>
                                    Envoyer lien de réinitialisation
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
        </div>
    </div>

</div>
