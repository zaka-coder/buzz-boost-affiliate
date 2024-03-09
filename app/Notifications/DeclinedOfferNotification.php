<?php

namespace App\Notifications;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeclinedOfferNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $offer;

    /**
     * Create a new notification instance.
     */
    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
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
        $name = explode(' ', $notifiable->name)[0];
        return (new MailMessage)
        ->greeting("Hello, {$name}")
        ->line("Sorry, your offer has been declined for the item {$this->offer?->product?->name}. Please proceed with the payment.")
        // ->action('Upload Gallery',
        //     route('race-director.events.gallery.index', ['event' => $this->event->id])
        // )
        ->line('Thank you for using our platform!');

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
