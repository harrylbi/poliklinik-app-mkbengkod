<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class QueueUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $id_jadwal;
    public int|string $sedang_dilayani;
    public string $action;
    public int|null $no_antrian;

    public function __construct(int $id_jadwal, int|string $sedang_dilayani, string $action = 'updated', ?int $no_antrian = null)
    {
        $this->id_jadwal = $id_jadwal;
        $this->sedang_dilayani = $sedang_dilayani;
        $this->action = $action;
        $this->no_antrian = $no_antrian;

        Log::info('Broadcasting QueueUpdated', [
            'id_jadwal' => $id_jadwal,
            'sedang_dilayani' => $sedang_dilayani,
            'action' => $action,
            'no_antrian' => $no_antrian,
        ]);
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
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
