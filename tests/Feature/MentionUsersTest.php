<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use RefreshDatabase;

    public function test_mentioned_users_get_notified()
    {
        $user1 = create('App\User', ['name' => 'user1']);
        $this->signIn($user1);
        $user2 = create('App\User', ['name' => 'user2']);

        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => "@$user2->name "]);

        $this->json('post', $thread->path() . '/replies', $reply->toArray());

        $this->assertCount(1, $user2->notifications);
    }

    public function  test_it_fetches_mentioned_users_for_auto_complete()
    {
        create('App\User', ['name' => 'Sababa']);
        create('App\User', ['name' => 'NotSababa']);

        $response = $this->json('GET', '/api/users', ['name'=>'sa'])->json();
        
        $this->assertEquals([['value' => 'Sababa']], $response);
        $this->assertCount( 1, $response);
    }
}
