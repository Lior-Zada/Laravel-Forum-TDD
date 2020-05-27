<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LockThreadTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_administrator_can_lock_a_thread()
    {
        $this->signIn();

        $this->withoutExceptionHandling();
        
        $thread = create('App\Thread');

        $thread->lock();

        $this->post($thread->path() . '/replies', [
            'body' => 'Test',
            'user_id' => auth()->id()
        ])->assertStatus(422);
    }
}
