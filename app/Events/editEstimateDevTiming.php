<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

use App\Estimate;

class editEstimateDevTiming implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $estimate;
            
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Estimate $estimate)
    {
        $this->estimate = $estimate;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {        
        return new PrivateChannel('estimates.'.$this->estimate->id);
    }
    
    public function broadcastWith()
    {     
        return [
            'id' => $this->estimate->id,
            'status' => $this->estimate->view,
            'timing' => $this->estimate->timing
        ];
     }
     
}
