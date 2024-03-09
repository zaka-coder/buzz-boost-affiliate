<?php

namespace App\Notifications;

use App\Models\Product;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerPaymentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $product;
    protected $buyer;
    protected $account;

    /**
     * Create a new notification instance.
     */
    public function __construct(Product $product, User $buyer, string $account)
    {
        $this->product = $product;
        $this->buyer = $buyer;
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
            ->line("Your item {$this->product->name} has been sold and the payment has been received in your {$this->account} account from {$this->buyer->name}.")
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
