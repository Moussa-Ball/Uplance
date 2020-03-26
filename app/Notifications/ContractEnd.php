<?php

namespace App\Notifications;

use Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ContractEnd extends Notification
{
    use Queueable;

    private $for_who;
    private $contract;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($contract, $for_who)
    {
        $this->for_who = $for_who;
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
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->for_who === 'freelancer') {
            return (new MailMessage)
                ->greeting(' ')
                ->subject("The {$this->contract->title} contract is now closed.")
                ->line("The {$this->contract->title} contract is already marked as completed. The client will leave you a review. Thank you for doing your business on uplance, we wish you the best success on uplance.")
                ->action('See the contract', url(route('contracts.show', $this->contract->id)));
        } else {
            return (new MailMessage)
                ->greeting(' ')
                ->subject("The {$this->contract->title} contract is now closed.")
                ->line("The {$this->contract->title} contract is already marked as completed. Your review was branded on the freelancer profile. Your project is no longer online. Thank you for doing your business on uplance we wish you the best.")
                ->action('See the contract', url(route('contracts.show', $this->contract->id)));
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        if ($this->for_who === 'freelancer') {
            return [
                'link' => route('contracts.show', $this->contract->id),
                'content' => "The {$this->contract->title} contract is already marked as completed. 
                    The client will leave you a review. Thank you for doing your business on uplance, 
                    we wish you the best success on uplance."
            ];
        } else {
            return [
                'link' => route('contracts.show', $this->contract->id),
                'content' => "The {$this->contract->title} contract is already marked as completed. 
                    Your review was branded on the freelancer profile. Your project is no longer online. 
                    Thank you for doing your business on uplance we wish you the best."
            ];
        }
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
