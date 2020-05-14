<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AddAvatarTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_cannot_add_avatars()
    {
        $this->json('POST', 'api/users/1/avatar')
            ->assertStatus(401);
    }

    public function test_only_valid_avatar_may_be_uploaded()
    {
        $this->signIn();

        $this->json('POST', "api/users/{auth()->user()->id}/avatar", [
            'avatar' => 'not-valid-image'
        ])
            ->assertStatus(422);
    }

    // https://laravel.com/docs/7.x/http-tests#testing-file-uploads
    public function test_authenticated_users_can_add_avatars()
    {
        $this->signIn();

        Storage::fake('public');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $this->json('POST', "api/users/{auth()->user()->id}/avatar", [
            'avatar' => $file
        ]);

        $this->assertEquals('avatars/' . $file->hashName(), auth()->user()->avatar_path);

        Storage::disk('public')->assertExists('avatars/' . $file->hashName());

    }

    public function test_a_user_has_an_avatar_or_default_pic()
    {
        $user = create('App\User');

        $this->assertEquals(asset('storage/avatars/default.jpg'), $user->avatar());        

        $user = create('App\User', ['avatar_path' => 'avatars/me.jpg']);

        $this->assertEquals(asset('storage/avatars/me.jpg'), $user->avatar());
    }
}
