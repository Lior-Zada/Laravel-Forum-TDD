<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class UsersAvatarController extends Controller
{
    public function store()
    {
        request()->validate([
            'avatar' => 'required|image'
        ]);

        auth()->user()->update([
            'avatar_path' => request()->file('avatar')->store('avatars', 'public')
        ]);

        return response([], 204);
    }
}
