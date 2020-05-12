<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use App\User;
use App\Notifications\YouWereMentioned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifiyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {
        collect($event->reply->mentionedUsers())
            ->map(function($name){// will either return the user collection or null.
                return User::whereName($name)->first();    
            })
            ->filter() // empty filter will filter out the null values
            ->each
            ->notify(new YouWereMentioned($event->reply));
    }
}
