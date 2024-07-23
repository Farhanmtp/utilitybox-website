<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\HtmlString;

class DealCreatedNotification extends Notification
{
    use Queueable;

    /**
     * @var
     */
    public $email;
    /**
     * @var
     */
    public $deal;

    /**
     * Create a new notification instance.
     */
    public function __construct($deal)
    {
        $this->deal = $deal;
    }

    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     * @return array|string
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $url = route('admin.deals.edit', $this->deal->id);

        $supplier = data_get($this->deal, 'contract.newSupplier');
        $firstName = data_get($this->deal, 'customer.firstName');
        $lastName = data_get($this->deal, 'customer.lastName');
        $email = data_get($this->deal, 'customer.email');

        $customer_name = trim($firstName . ' ' . $lastName);

        $app_name = settings('app.name', config('app.name'));

        return (new MailMessage)
            ->subject("New Deal Created for $supplier - Action Required")
            ->greeting('Dear Concern,')
            ->line(new HtmlString("A deal for <b>$supplier</b> has been created by <b>$customer_name</b> ($email)."))
            ->line("The details have been saved in the CMS for your review. To view the deal, please click on the link below:")
            ->action('Open Deal', $url)
            ->salutation(new HtmlString("Best Regards,<br>$app_name" ));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $notifiable->toArray();
    }
}
