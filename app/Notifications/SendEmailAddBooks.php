<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEmailAddBooks extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $book;
    public function __construct($book)
    {
        //
        $this->book=$book;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('new book')
                    ->greeting("hello {{$notifiable->name}}")
                    ->line("new book to the notification #{{$this->book->name}}.")
                    ->action('Notification Action', url("/api/book/{$this->book->id}"))
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
                'name' => $this->book->name,
                'book_id'=>$this->book->id,
                'user_id'=>$notifiable->id,
        ];
    }
}
