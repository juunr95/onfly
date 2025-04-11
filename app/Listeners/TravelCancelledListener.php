<?php

namespace App\Listeners;

use App\Events\TravelCancelled;
use App\Mail\TravelCreatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class TravelCancelledListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TravelCancelled $event): void
    {
        $travel = $event->travel;
        $user = $travel->order->requester;

        if (!$travel) {
            return;
        }

        Mail::to($user)->queue(new TravelCreatedMail($event->travel));
    }
}
