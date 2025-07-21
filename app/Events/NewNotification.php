<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewNotification
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;
    public User $user;

    public function __construct(User $user, string $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Channel yang akan di-broadcast.
     * Hanya user yang terotentikasi dan memiliki ID ini yang bisa mendengarkan.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.' . $this->user->id),
        ];
    }

    /**
     * Nama event yang akan didengarkan di frontend.
     */
    public function broadcastAs(): string
    {
        return 'new-notification';
    }
}
