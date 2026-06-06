<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportCardGenerated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
public function toMail($notifiable)
{
    return (new MailMessage)
        ->subject('Your Child\'s Report Card is Ready!')
        ->greeting('Hello!')
        ->line('The report card for the 1st Term 2025/2026 has been generated.')
        ->line('You can log in to the student portal to view and download your child\'s results.')
        ->action('View Report Card', route('dashboard')) // Links to student dashboard
        ->line('Thank you for choosing our school!');
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
