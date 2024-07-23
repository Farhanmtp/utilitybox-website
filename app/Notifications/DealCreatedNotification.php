<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
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
        $url = $this->updaeUrl();

        $supplier = data_get($this->deal, 'contract.newSupplier');
        $firstName = data_get($this->deal, 'customer.firstName');
        $lastName = data_get($this->deal, 'customer.lastName');

        $app_name = settings('app.name', config('app.name'));

        if ($this->deal->user) {
            $customer_name = $this->deal->user->name;
        } else {
            $customer_name = trim($firstName . ' ' . $lastName);
        }

        return (new MailMessage)
            ->subject("New Deal Created for $supplier")
            ->greeting("Dear $customer_name,")
            ->line(new HtmlString("A deal for <b>$supplier</b> has been created"))
            ->line("To update the deal, please click on the link below:")
            ->action('Update Deal', $url)
            ->salutation(new HtmlString("Best Regards,<br>$app_name"));
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


    /**
     * Get the email verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function updaeUrl()
    {
        $token = Crypt::encryptString($this->deal->id);

        return url(route('contract', $token, false));
    }
}
