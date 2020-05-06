<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChannelTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_channel_consists_of_threads()
    {
        // possible in 2 ways.
        // 1. make sure this relationship returns a collection.
        // 2. create a channel, and a thread, fetch all threads for the channel, and make sure that the created thread is in the collection.
        
        $channel = create('App\Channel');
        $thread = create('App\Thread', ['channel_id' => $channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }
}
