<?php

namespace App\Notifications;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BuyerPaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $product;
    protected $account;

    /**
     * Create a new notification instance.
     */
    public function __construct(Product $product, string $account)
    {
        $this->product = $product;
        $this->account = $account;
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
            ->greeting("Hello, {$notifiable->name}")
            ->line("You have successfully purchased the item {$this->product->name} and the payment has been sent to the seller {$this->account} account.")
            ->line('Thank you for using our application!');
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
