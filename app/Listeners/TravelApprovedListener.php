<?php

namespace App\Listeners;

use App\Events\TravelApproved;
use App\Mail\TravelApprovedMail;
use App\Mail\TravelCancelledMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class TravelApprovedListener
{

    /**
     * Handle the event.
     */
    public function handle(TravelApproved $event): void
    {
        $user = $event->travel->order->requester;

        if (!$user) {
            return;
        }

        Mail::to($user)->queue(new TravelApprovedMail($event->travel));
    }
}
