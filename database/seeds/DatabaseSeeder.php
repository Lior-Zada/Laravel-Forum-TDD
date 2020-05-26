<?php

use App\Trending;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        $threads = factory('App\Thread', 50)->create();

        $threads->each(function ($thread) {
            factory('App\Reply', 10)->create(['thread_id' => $thread->id]);
        });

        // create my test user
        create('App\User', [
            'name' => 'LiorZada',
            'email' => 'lior305@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123123123'),
            'remember_token' => Str::random(10),
        ]);

        $trending = new Trending();

        //reset redis cached data
        $trending->reset();
        // set first 3 created threads as trending
        collect($threads)
            ->filter(function ($value, $key) {
                return $key < 3;
            })
            ->each(function ($thread) use ($trending) {
                $trending->push($thread);
            });
    }
}
