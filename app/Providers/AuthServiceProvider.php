<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
  

        ResetPassword::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->greeting('Bonjour, c\'est DamsoStore')
                ->subject('Réinitialisation du mot de passe')
                ->line('Vous recevez cet e-mail car nous avons reçu une demande de réinitialisation du mot de passe de votre compte.')
                ->action('Réinitialiser mon mot de passe ', url(route('password.reset', ['token' => $url,'email' => $notifiable->getEmailForPasswordReset()], false)))
                ->line("Ce lien de réinitialisation du mot de passe expirera dans 60 minutes.")
                ->line('Si vous n\'avez pas demandé de réinitialisation du mot de passe, aucune autre action n\'est requise de votre part.');
        });


        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->greeting('Bonjour, c\'est DamsoStore')
                ->subject('Vérification d\'adresse email')
                ->line('il suffit d\'appuyer sur le bouton en dessous pour vérifier votre adresse mail')
                ->action('Verifier mon adresse mail !', $url)
                ->line("si vous n'avez crée aucun compte aucune action n'est requise de votre part");
        });
    }
}
