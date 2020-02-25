<?php

namespace App\Notifications;

use App\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ProposalSend extends Notification implements ShouldQueue
{
    use Queueable;

    private $proposal;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Proposal $proposal)
    {
        $this->proposal = $proposal;
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
        return (new MailMessage)
            ->subject('You have a new proposal for your project.')
            ->greeting('Hi ' . $this->proposal->user->name)
            ->line($this->proposal->user->name . ' sent you a proposal for the project: '
                . $this->proposal->job->project_name)
            ->action('See the proposal', route('proposals.list', $this->proposal->job->hashid))
            ->salutation('Bests regards,\nUplance');
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
            'name' => $this->proposal->user->name,
            'project_name' => $this->proposal->job->project_name,
            'link' => route('proposals.list', $this->proposal->job->hashid)
        ];
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
