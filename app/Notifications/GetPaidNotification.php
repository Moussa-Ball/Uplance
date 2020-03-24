<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class GetPaidNotification extends Notification
{
    use Queueable;

    private $user;
    private $withdrawal;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($withdrawal, $user)
    {
        $this->user = $user;
        $this->withdrawal = $withdrawal;
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
        $number_formatter = new \NumberFormatter('en_US', \NumberFormatter::CURRENCY);
        return (new MailMessage)
                    ->greeting('Hi '.$this->user->name.'!')
                    ->line('Your '.$number_formatter->formatCurrency($this->withdrawal->amount, 'USD').' withdrawal is in progress. Your money will be sent directly to your paypal account. Thank you for checking your paypal balance in a few minutes. We wish you the best success on uplance.');
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
