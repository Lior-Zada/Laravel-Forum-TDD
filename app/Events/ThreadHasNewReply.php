<?php

namespace App\Events;

use App\Reply;
use App\Thread;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ThreadHasNewReply
{
    use Dispatchable, SerializesModels;

    public $thread;
    public $reply;
 
    public function __construct(Thread $thread, Reply $reply)
    {
        $this->thread = $thread;
        $this->reply = $reply;
    }

}
