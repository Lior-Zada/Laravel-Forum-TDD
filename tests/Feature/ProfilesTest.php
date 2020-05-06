<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_has_a_profile()
    { 
        $user = create('App\User');

        $this->get(url("/profiles/$user->name"))
            ->assertSee($user->name);
    }

    public function test_profiles_displays_threads_created_by_the_associated_user()
    {
        $this->signIn();

        $threadCreatedByUser = create('App\Thread', ['user_id' => auth()->id()]);

        $this->get(url('/profiles/'. auth()->user()->name))
            ->assertSee($threadCreatedByUser->title)
            ->assertSee($threadCreatedByUser->body);
    }
}
