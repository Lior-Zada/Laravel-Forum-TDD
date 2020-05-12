<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
// use PHPUnit\Framework\TestCase; // this is the default, and its not working!!!

class ReplyTest extends TestCase
{

    // We use database in memory, so we have to use this:
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->reply = create('App\Reply');
    }

    public function test_it_has_an_owner()
    {
        $this->assertInstanceOf('App\User', $this->reply->owner);
    }

    public function test_it_knows_a_reply_was_just_published()
    {
        $this->assertTrue($this->reply->wasJustPublished());
    }

    public function test_it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = create('App\Reply', ['body' => '@JohnDoe mentiones @JaneDoe']);

        $this->assertEquals(['JohnDoe', 'JaneDoe'], $reply->mentionedUsers());
    }


}
