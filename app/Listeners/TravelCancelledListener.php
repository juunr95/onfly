<?php

namespace App\Listeners;

use App\Events\TravelCancelled;
use App\Mail\TravelCancelledMail;
use Illuminate\Support\Facades\Mail;

class TravelCancelledListener
{

    /**
     * Handle the event.
     */
    public function handle(TravelCancelled $event): void
    {
        $user = $event->travel->order->requester;

        if (!$user) {
            return;
        }

        Mail::to($user)->queue(new TravelCancelledMail($event->travel));
    }
}
