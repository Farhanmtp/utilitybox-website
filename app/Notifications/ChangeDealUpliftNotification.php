<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\HtmlString;

class ChangeDealUpliftNotification extends Notification
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
    public function __construct($email, $deal)
    {
        $this->email = $email;

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
        $url = $this->resetUrl();

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
            ->greeting("Dear $customer_name,")
            ->subject(Lang::get('Your profile is ready to be viewed'))
            ->line(Lang::get("I hope this email finds you well! I'm excited to share that your profile is officially live and ready for your review."))
            ->line(Lang::get("To access your quotations and finalise the sign-up process, please click on the link provided below:"))
            ->action(Lang::get('Update Quote'), $url)
            ->line(Lang::get("Need a hand or have any questions along the way? Don't hesitate to reach out – I'm here to help!"))
            ->salutation(new HtmlString("Warm regards <br>" . $app_name));
    }

    /**
     * Get the email verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function resetUrl()
    {
        $token = Crypt::encryptString($this->deal->id);

        return url(route('contract', $token, false));
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
