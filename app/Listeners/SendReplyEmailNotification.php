<?php

namespace App\Listeners;

use App\Events\NewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewReplyMail;

class SendReplyEmailNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewReply  $event
     * @return void
     */
    public function handle(NewReply $event)
    {
        // NOTE: Ideally we should use mailables and queues, but I kept it simple
        // for this test assignment
        Mail::to($event->reply->thread->author->email)
            ->send(new NewReplyMail($event->reply));
    }
}
