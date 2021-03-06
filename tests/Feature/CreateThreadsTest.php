<?php

namespace Tests\Feature;

use App\Activity;
use App\Rules\Recaptcha;
use App\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
       
        // Bind the Recaptcha class to the mock, and makes sure that when reaching for Recaptcha, only the SAME instance will be returned
        app()->singleton(Recaptcha::class, function(){
            // Create a copy of Recaptcha class
            $recaptchaMock = Mockery::mock(Recaptcha::class);
            // imitate a call to 'passes', bypass it, return true. - so that we dont have to use the google API every time.
            $recaptchaMock->shouldReceive('passes')->andReturn(true);
            return $recaptchaMock;
        });
    }

    public function test_guest_cannot_create_thread()
    {
        // $this->withExceptionHandling(); // This is the default behaviour
        $this->get(url('/threads/create'))
            ->assertRedirect('/login');

        $this->post(url('threads'))
            ->assertRedirect('/login');
    }

    public function test_a_user_must_confirm_email_before_posting_a_thread()
    {
        $user = create('App\User', ['email_verified_at' => null]);
        
        $this->signIn($user);
        
        $thread = make('App\Thread');
        
        $this->post(url('threads'), $thread->toArray())
            ->assertRedirect('/email/verify')
            ->assertSessionHas('flash');
    }

    public function test_user_can_create_thread()
    {
        // raw() -> instead of make() -> it will return an array of the values. we use make now because we need the instance.
        $response = $this->publishThread(['title' => 'TestTitle', 'body' => 'TestBody']);

        $this->get($response->headers->get('Location'))
            ->assertSee('TestTitle')
            ->assertSee('TestBody');
    }

    public function test_unauthorized_user_cannot_delete_threads()
    {  
        // guest cannot delete anything
        $thread = create('App\Thread');
        $this->delete( $thread->path())->assertRedirect('/login');

        //user cannot delete threads other than his own.
        $this->signIn();
        $this->delete( $thread->path())->assertStatus(403);

    }

    public function test_authorized_user_can_delete_threads()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id, 'user_id' => auth()->id()]);

        $response = $this->delete( $thread->path());

        $response->assertRedirect('/threads');

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, Activity::count());
    }

    public function test_a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
    }

    public function test_a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    public function test_a_thread_requires_a_unique_slug()
    {
        $this->signIn();

        $thread = create('App\Thread', ['title' => 'Test title']);
        
        $this->assertEquals($thread->fresh()->slug, 'test-title');

        $thread = $this->postJson('threads', $thread->toArray() + ['g-recaptcha-response' => 'test'])->json();
        
        $this->assertEquals("test-title-{$thread['id']}", $thread['slug']);
    }

    public function test_a_thread_that_ends_with_a_number_should_generate_proper_slug()
    {
        $this->signIn();

        $thread = create('App\Thread', ['title' => 'Test title 42']);

        $thread = $this->postJson('threads', $thread->toArray() + ['g-recaptcha-response' => 'test'])->json();

        $this->assertEquals("test-title-42-{$thread['id']}", $thread['slug']);
    }

    
    public function test_requires_to_pass_recaptcha_validation()
    {
        // unbind the singleton we made in the setUp
        unset(app()[Recaptcha::class]);

        $this->publishThread(['g-recaptcha-response' => 'testing'])
            ->assertSessionHasErrors(['g-recaptcha-response']);
    }

    public function test_a_thread_requires_a_valid_channel_id()
    {
        // factory('App\Channel', 2)->create(); // its not really needed

        // must supply a channel_id
        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        // channel_id must exist 
        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors(['channel_id']);
    }

    private function publishThread($overrides = [])
    {
        // Laravel will throw a validation exception, and it will throw an "error" variable into the session, with the missing attribute.
        $this->signIn();
        $thread = make('App\Thread', $overrides);

        return $this->post(url('threads'), $thread->toArray() + ['g-recaptcha-response' => 'test']);
    }
}
