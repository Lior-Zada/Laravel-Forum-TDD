<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ThreadsTest extends TestCase
{
    // We use database in memory, so we have to use this:
    use RefreshDatabase;
    protected $thread;

    public function setUp(): void
    {
        parent::setUp();
        $this->thread = create('App\Thread');
    }

    public function test_user_can_browse_threads()
    {
        $response = $this->get('/threads');
        $response->assertSee($this->thread->title);
    }

    public function test_user_can_browse_single_thread()
    {
        $response = $this->get($this->thread->path());
        $response->assertSee($this->thread->title);
    }

    public function test_user_can_filter_threads_by_channel()
    {
        $channel = create('App\Channel');

        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');

        $this->get(url('/threads/' . $channel->slug))
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel->title);
    }

    public function test_a_user_can_filter_threads_by_any_username()
    {
        $this->signIn(create('App\User', ['name' => 'LiorZada']));

        $threadByLior = create('App\Thread', ['user_id' => auth()->id()]);
        $threadNotByLior = create('App\Thread');

        $this->get(url('threads?by=LiorZada'))
            ->assertSee($threadByLior->title)
            ->assertDontSee($threadNotByLior->title);
    }

    public function test_a_user_can_filter_threads_by_popularity()
    {
        $this->withoutExceptionHandling();
        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);

        $threadWithThreeReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);

        $threadWithZeroReplies = $this->thread;

        $response = $this->getJson(url('threads?popular=1'))->json();
        $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
    }
    
    public function test_user_can_filter_threads_that_are_unanswered()
    {
        $thread = create('App\Thread');
        create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->getJson(url('threads?unanswered=1'))->json();

        $this->assertCount(1, $response);

    }

    public function test_user_can_request_all_replies_for_a_given_thread()
    {
        $threadWithTwoReplies = create('App\Thread');
        create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);

        $response = $this->getJson($threadWithTwoReplies->path() . '/replies')->json();
        
        $this->assertCount(2, $response['data']);
        $this->assertEquals(2, $response['total']);
    }

    public function test_a_thread_can_be_subscribed_to()
    {
        $thread = create('App\Thread');
        $thread->subscribe($userId = 1);
        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());
    }

    public function test_a_thread_can_be_unsubscribed_from()
    {
        $thread = create('App\Thread');
        $thread->subscribe($userId = 1);
        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());
        
        $thread->unsubscribe($userId);
        $this->assertEquals(0, $thread->subscriptions->count());

    }

    public function test_if_it_knows_an_authenticated_user_is_subscribed_to_it()
    {
        $this->signIn();
        
        $thread = create('App\Thread');
        
        $this->assertFalse( $thread->isSubscribedTo());
        
        $thread->subscribe(auth()->id());

        $this->assertTrue( $thread->isSubscribedTo());
    }
 
}
