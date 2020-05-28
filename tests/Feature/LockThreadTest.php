<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LockThreadTest extends TestCase
{
    use RefreshDatabase;

    public function test_once_a_thread_is_locked_no_replies_can_be_added()
    {
        $this->signIn();
        
        $thread = create('App\Thread', ['locked'=>true]);

        $this->post($thread->path() . '/replies', [
            'body' => 'Test',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }

    public function test_non_administrator_may_not_lock_a_thread()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread))
            ->assertStatus(403);

        $this->assertFalse((bool) $thread->fresh()->locked);
    }

    public function test_an_administrator_may_lock_a_thread()
    {
        $this->signIn(create('App\User', ['name' => 'LiorZada']));

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread))
            ->assertStatus(200);

        $this->assertTrue($thread->fresh()->locked);
    }

    public function test_an_administrator_may_unlock_a_thread()
    {
        $this->signIn(create('App\User', ['name' => 'LiorZada']));

        $thread = create('App\Thread', ['user_id' => auth()->id(), 'locked' => true]);

        $this->delete(route('locked-threads.destroy', $thread))
            ->assertStatus(200);

        $this->assertFalse($thread->fresh()->locked);
    }
    
}
