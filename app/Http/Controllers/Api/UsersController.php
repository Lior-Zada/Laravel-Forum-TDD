<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

use function PHPSTORM_META\map;

class UsersController extends Controller
{
    public function index()
    {
        $search = request('name');

        return User::where('name', 'LIKE', "$search%")
            ->take(5)
            ->pluck('name')
            ->map(function ($user) {
                return ['value' => $user];
            });
    }
}
