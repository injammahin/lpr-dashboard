<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class AlertTriggered implements ShouldBroadcast
{
    use SerializesModels;

    public $alert;
    public $detection;

    public function __construct($alert, $detection)
    {
        $this->alert = $alert;
        $this->detection = $detection;
    }

    public function broadcastOn()
    {
        return new Channel('alerts');
    }

    public function broadcastAs()
    {
        return 'alert.triggered';
    }
}
