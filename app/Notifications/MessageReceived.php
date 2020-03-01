<?php

namespace App\Notifications;

use App\User;
use Auth;
use Illuminate\Bus\Queueable;
use Cmgmyr\Messenger\Models\Message;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MessageReceived extends Notification
{
    use Queueable;

    private $msg;
    private $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Message $msg, User $user)
    {
        $this->msg = $msg;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('You have unread message(s) regarding: ' . $this->msg->thread->subject)
            ->greeting('Hi ' . $this->user->name)
            ->line($this->msg->user->name . ' sent you a message.')
            ->line('Subject: ' . $this->msg->thread->subject)
            ->action('Reply', route('messages.thread', $this->msg->thread->id));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
