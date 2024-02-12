<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderReception extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private string $nom, private string $numero,private string $email, private string $adresse)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Nouvelle commande sur Damsostore !')
                    ->greeting('Nous accusons réception d\'une nouvelle commande')
                    ->line('voici quelques informations : ')
                    ->line('nom : '.$this->nom)
                    ->line('numero : '.$this->numero)
                    ->line('adresse mail : '.$this->email)
                    ->line('lieu de livraison : '.$this->adresse)
                    ->line('merci et à tout de suite !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
