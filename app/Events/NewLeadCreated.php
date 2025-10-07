<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewLeadCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userPhone;
    public $leadName;
    public $quantity;

    public function __construct($userPhone, $leadName, $quantity)
    {
        $this->userPhone = $userPhone;
        $this->leadName = $leadName;
        $this->quantity = $quantity;
    }
}