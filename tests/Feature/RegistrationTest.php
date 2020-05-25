<?php

namespace Tests\Feature;

use App\Email\EmailVerificationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_when_registering_a_confirmation_email_is_sent()
    {
        Event::fake();

        $this->post(route('register'), [
            'name' => 'John',
            'email' => 'johndoe@test.com',
            'password' => 'passwordtest',
            'password_confirmation' => 'passwordtest'
        ]);

        Event::assertDispatched(Registered::class);
    }

    public function test_email_verification_works()
    {
        $notification = new EmailVerificationNotification();

        $user = create('App\User', ['email_verified_at' => null]);

        $uri = $notification->verificationUrl($user);

        $this->assertSame(null, $user->email_verified_at);

        $this->signIn($user)->get($uri);

        $this->assertNotNull($user->email_verified_at);
    }
}
