<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class OfferContractActivated extends Notification
{
    use Queueable;

    private $contract;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($contract)
    {
        $this->contract = $contract;
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
            ->greeting('Hello!')
            ->line($this->contract->to->first_name.' '
                .$this->contract->to->last_name.' accepted your offer regarding the contract `'
                .$this->contract->title.'`. 
                The contract has just been active and is underway.')
            ->action('See the contract', route('contracts.view', $this->contract->id))
            ->line('Good luck!');
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
