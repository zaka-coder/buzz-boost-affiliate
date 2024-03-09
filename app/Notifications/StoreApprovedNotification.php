<?php

namespace App\Notifications;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StoreApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $store;

    /**
     * Create a new notification instance.
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
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
        if($this->store->approved){
            return (new MailMessage)
        ->greeting("Hello, {$notifiable->name}")
        ->line("Congratulations, Your store {$this->store->name} has been approved by the admin.")
        ->line('Thank you for using our platform!');
        } else  {
            return (new MailMessage)
        ->greeting("Hello, {$notifiable->name}")
        ->line("Sorry,Your store {$this->store->name} has been rejected by the admin.")
        ->line('Thank you for using our platform!');
        }

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
