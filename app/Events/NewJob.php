<?php

namespace App\Events;

use App\Job;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class NewJob implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Contains the job information. Contains the details of job.
     *
     * @var Job
     */
    private $job;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new channel('NewJob');
    }

    /**
     * Send the information to the socket server.
     *
     * @return array Contains job data published online.
     */
    public function broadcastWith(): array
    {
        return [
            'project_name' => $this->job->project_name,
            'category' => ($this->job->categories()->first()) ? $this->job->categories()->first()->name : "",
            'minimum' => $this->job->minimum,
            'maximum' => $this->job->maximum,
            'location' => $this->job->location,
            'project_type' => $this->job->project_type,
            'skills' => $this->job->skills,
            'description' => $this->job->description,
        ];
    }
}
