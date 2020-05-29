<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateThreadsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    public function test_a_thread_can_be_updated_by_its_creator()
    {
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'New title',
            'body' => 'New body',
        ]);

        tap($thread->fresh(), function($thread){
            $this->assertEquals('New title', $thread->title);
            $this->assertEquals('New body', $thread->body);
        });
    }

    public function test_a_thread_requires_title_and_body_to_be_updated()
    {
        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->patch($thread->path(), [
            'title' => 'New title',
        ])->assertSessionHasErrors(['body']);

        $this->patch($thread->path(), [
            'body' => 'New body',
        ])->assertSessionHasErrors(['title']);
    }

    public function test_unauthorized_user_cannot_update_threads()
    {
        $thread = create('App\Thread', ['user_id' => create('App\User')->id]);

        // trying to update with the signed in user (in setUp) instead with the creator's id.
        $this->patch($thread->path(), [])->assertStatus(403);
    }
}
