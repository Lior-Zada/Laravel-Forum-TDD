<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->thread = create('App\Thread');
    }

    public function test_a_thread_has_correct_path()
    {

        $thread = create('App\Thread');

        $this->assertEquals(url("threads/{$thread->channel->slug}/$thread->slug"), $thread->path());
    }

    public function test_a_thread_has_a_creator()
    {
        $this->assertInstanceOf(User::class, $this->thread->creator);
    }

    public function test_a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    public function test_a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    public function test_a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        // Fake Notification Facade made for testing.
        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body' => 'Foobar',
                'user_id' => create('App\User')->id
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    public function test_a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');
        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    public function test_a_thread_can_check_an_authenticated_user_has_read_all_the_replies()
    {
        $this->signIn();
        
        $thread = create('App\Thread');
        
        $user =  auth()->user();
        
        $this->assertTrue($thread->hasUpdadesFor($user));

        $user->read($thread);

        $this->assertFalse($thread->hasUpdadesFor($user));

    }

    public function test_a_thread_body_is_sanitized_automatically()
    {
        $thread = make('App\Thread', ['body' => '<script>alert(123)</script><h2>shouldStay</h2>']);
        $this->assertEquals($thread->body, '<h2>shouldStay</h2>');
    }

}
