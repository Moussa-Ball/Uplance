<?php

namespace App\Notifications;

use App\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProposalRejected extends Notification
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
            ->subject('Your proposal has been rejected.')
            ->greeting('Hi ' . $this->proposal->user->name)
            ->line("Your proposal for {$this->proposal->job->project_name} project has been rejected.")
            ->line("You can still continue to search for another job.")
            ->action("Find Job", route('jobs.index'))
            ->line("Good luck on uplance.")
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
            'project_name' => $this->proposal->job->project_name,
            'link' => route('jobs.show', $this->proposal->job->hashid)
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
