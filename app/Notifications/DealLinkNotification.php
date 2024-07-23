<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Lang;

class DealLinkNotification extends Notification
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
        return (new MailMessage)
            ->subject(Lang::get('Your deal created.'))
            ->line(Lang::get('Your quote has been generated. For this link you can update your quote.'))
            ->action(Lang::get('Update Quote'), $url)
            ->line(Lang::get('If you did not request for quote, no further action is required.'));
    }

    /**
     * Get the email verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function resetUrl()
    {
        $token = Crypt::encryptString($this->deal->id . ',' . $this->email);

        return url(route('compare', $token, false));
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
