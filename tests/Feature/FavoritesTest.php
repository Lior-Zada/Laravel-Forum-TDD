<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_cannot_favorite_any_reply()
    {
        $this->post(url('replies/1/favorites'))
        ->assertRedirect('/login');
    }

    public function test_a_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $this->post(url('replies/' . $reply->id . '/favorites'));

        $this->assertCount(1, $reply->favorites);
    }

    public function test_a_user_can_favorite_a_reply_once_only()
    {

        $this->signIn();

        $reply = create('App\Reply');

        $this->post(url('replies/' . $reply->id . '/favorites'));
        $this->post(url('replies/' . $reply->id . '/favorites'));

        $this->assertCount(1, $reply->favorites);
    }

    public function test_a_user_can_unfavorite_a_reply()
    {

        $this->signIn();

        $reply = create('App\Reply');

        $reply->favorite();

        // $this->post(url('replies/' . $reply->id . '/favorites'));
        // here
        // $this->assertCount(1, $reply->favorites);

        $this->delete(url('replies/' . $reply->id . '/favorites'));

        // You need to ask for a fresh instance of reply because it was already eagerLoaded @here.
        // $this->assertCount(0, $reply->fresh()->favorites);

        $this->assertCount(0, $reply->favorites);
    }
}
