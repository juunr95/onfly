<?php

namespace App\Listeners;

use App\Events\TravelCreated;
use App\Mail\TravelCreatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendTravelCreatedEmail
{

    /**
     * Handle the event.
     */
    public function handle(TravelCreated $event): void
    {
        $user = $event->travel->order->requester;

        if (!$user) {
            return;
        }

        Mail::to($user)->queue(new TravelCreatedMail($event->travel));
    }
}
