<?php

use App\Providers\RouteServiceProvider;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.auth')]
class extends Component
{
    public function sendVerification(): void
    {
        if (auth()->user()->hasVerifiedEmail()) {
            $this->redirect(
                session('url.intended', RouteServiceProvider::HOME),
                navigate: true
            );

            return;
        }

        auth()->user()->sendEmailVerificationNotification();

        session()->flash('status', 'verification-link-sent');
    }

    public function logout(): void
    {
        auth()->guard('web')->logout();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class=" bg-green-100  w-full h-screen flex justify-center items-center" >



    <div class="flex flex-col justify-center px-6 py-8 lg:px-8 w-96 rounded-md bg-white drop-shadow-2xl">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm flex flex-col items-center">
          <x-application-logo class="mx-auto w-auto fill-current h-9" />
          <h2 class="mt-2 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Vérification email</h2>
        </div>

        <div class="mt-4 w-10 sm:mx-auto sm:w-full sm:max-w-sm">
                        {{--  <livewire:auth.loginForm /> --}}
                    <div>

                            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                                <span class=" font-semibold ">Merci de vous être inscrit !</span>  <br> Avant de commencer, pourriez-vous vérifier votre adresse e-mail en cliquant sur le lien que nous venons de vous envoyer par e-mail ? Si vous n'avez pas reçu l'e-mail, nous vous en enverrons volontiers un autre.
                            </div>

                            @if (session('status') == 'verification-link-sent')
                                <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                                    Un nouveau lien de vérification a été envoyé à l'adresse e-mail que vous avez fournie lors de l'inscription.
                                </div>
                            @endif

                            <div class="mt-4 flex items-center justify-between">
                                <x-primary-button wire:click="sendVerification">
                                    Renvoyer un autre email
                                </x-primary-button>

                                <button wire:click="logout" type="submit" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                    Se déconnecter
                                </button>
                            </div>
                    </div>
        </div>
    </div>

</div>


