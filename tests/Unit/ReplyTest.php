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

    public function test_it_wraps_mentioned_username_within_anchor_tag()
    {
        $reply = make('App\Reply', [
            'body' => '@JaneDoe was mentioned'
        ]);

        $this->assertEquals(
            '<a href="/profile/JaneDoe">@JaneDoe</a> was mentioned',
            $reply->body
        );
    }

    public function test_it_knows_if_it_is_the_best_reply()
    {
        $reply = create('App\Reply');

        $this->assertFalse($reply->isBest());

        $reply->thread->update(['best_reply_id' => $reply->id]);

        $this->assertTrue($reply->isBest());
    }

}
