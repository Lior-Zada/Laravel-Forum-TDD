<?php

namespace Tests\Feature;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
    }
    public function test_guest_cannot_add_replies()
    {

        $this->post('threads/test-channel/1/replies', [])
            ->assertRedirect('/login');
    }

    public function test_authenticated_user_can_add_replies()
    {

        $this->signIn();

        $thread = create('App\Thread');

        // make() -save to memory, create() -save to database
        $reply = make('App\Reply');

        $this->post($thread->path() . '/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        // Must get fresh instance of $thread because we've updated it behind the scences
        $this->assertEquals(1,  $thread->fresh()->replies_count);
    }

    public function test_a_reply_requires_body()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(422);
    }

    public function test_unauthorized_user_cannot_delete_reply()
    {
        $reply = create('App\Reply');

        $this->delete("replies/$reply->id")
            ->assertRedirect('/login');


        $this->signIn()
            ->delete("replies/$reply->id")
            ->assertStatus(403);
    }

    public function test_authorized_user_can_delete_reply()
    {
        $this->signIn();
        $user_id = auth()->id();

        $reply = create('App\Reply', ['user_id' => $user_id]);

        // 302 "Found" is common for performing redirect
        $this->delete("replies/$reply->id")->assertStatus(302);

        $this->assertDatabaseMissing('replies', [
            'id' => $reply->id
        ]);

        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    public function test_unauthorized_user_cannot_update_a_reply()
    {
        $reply = create('App\Reply');

        $this->patch("/replies/$reply->id")
            ->assertRedirect('/login');

        $this->signIn()
            ->patch("/replies/$reply->id", ['body' => 'test'])
            ->assertStatus(403);
    }

    public function test_authorized_user_can_update_a_reply()
    {
        $this->signIn();

        $user_id = auth()->id();

        $reply = create('App\Reply', [
            'user_id' => $user_id,
            'body' => 'former-body'
        ]);

        $updatedReply = 'body-changed!';

        $this->patch("/replies/$reply->id", [
            'body' => $updatedReply
        ]);

        $this->assertDatabaseHas('replies', [
            'id' => $reply->id,
            'body' => $updatedReply
        ]);
    }

    public function test_a_user_cannot_spam_our_threads()
    {
        $this->signIn()->withoutExceptionHandling();

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => 'This is spam'
        ]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(422);
    }

    public function test_users_cannot_reply_more_than_once_a_minute()
    {
        $this->signIn();

        $thread = create('App\Thread');

        $reply = make('App\Reply',[
            'body' => 'Leaving reply.',
            'thread_id' => $thread->id
        ]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(200);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertStatus(422);
    }
}
