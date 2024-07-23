<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\HtmlString;

class EmailVerificationNotification extends Notification
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
        $url = $this->verificationUrl();

        $name = $this->deal->customer_name ?? $this->deal->contact['firstName'] ?? 'Concern';

        $app_name = settings('app.name', config('app.name'));
        return (new MailMessage)
            ->subject(Lang::get('Verify Your Email Address'))
            ->greeting('Dear ' . $name)
            ->line("Thank you for signing up with $app_name! To complete your registration, please verify your email address by clicking the link below:")
            ->action('Verify Email', $url)
            ->line('If you are unable to click the link, please copy and paste it into your web browser.')
            ->line('This step is crucial to ensure the security of your account and to keep you informed about important updates.')
            ->line("If you did not sign up for $app_name, please disregard this email.")
            ->salutation(new HtmlString('Regards,<br>' . $app_name));
    }

    /**
     * Get the email verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function verificationUrl(): string
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