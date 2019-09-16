<?php

namespace App\Events;

use App\Model\BorrowerLoan;
use App\Presenters\LoanPresenter;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LoanCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $borrowerLoan;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(BorrowerLoan $borrowerLoan)
    {
        $this->borrowerLoan = new LoanPresenter($borrowerLoan);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
