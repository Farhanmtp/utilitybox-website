<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class BookNowNotification extends Notification
{
    use Queueable;

    public $request;

    /**
     * Create a new notification instance.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
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
        $app_name = settings('app.name', config('app.name'));

        $url = trim($this->request->url);
        $message = trim($this->request->message);
        $subject = trim($this->request->subject);

        $mailMessage = (new MailMessage)
            ->subject(($subject ?: "Book Now") . ' - ' . $app_name)
            ->greeting('Dear Concern,')
            ->line(new HtmlString("<b>Name:</b> " . strip_tags($this->request->name)))
            ->line(new HtmlString("<b>Email:</b> " . strip_tags($this->request->email)))
            ->line(new HtmlString("<b>Phone:</b> " . strip_tags($this->request->phone)))
            ->line(new HtmlString("<b>Business Name:</b> " . strip_tags($this->request->business_name)));

        if ($url) {
            $mailMessage->line(new HtmlString("<b>Url:</b> <a href='$url' target='_blank'>$url</a>"));
        }

        if ($message) {
            $mailMessage->line(strip_tags($message));
        }

        $mailMessage->salutation(new HtmlString("Regards,<br>$app_name"));

        return $mailMessage;
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
