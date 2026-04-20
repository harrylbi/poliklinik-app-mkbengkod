<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $id_jadwal;
    public int|string $sedang_dilayani;

    public function __construct(int $id_jadwal, int|string $sedang_dilayani)
    {
        $this->id_jadwal = $id_jadwal;
        $this->sedang_dilayani = $sedang_dilayani;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        // Public channel — no auth needed for patients to listen
        return new Channel('queue-updates');
    }

    /**
     * The event name used by Echo on the frontend.
     */
    public function broadcastAs(): string
    {
        return 'QueueUpdated';
    }
}
