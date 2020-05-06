<?php

namespace Tests\Unit;

use App\Activity;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_is_recorded_when_thread_is_created()
    {
        $this->signIn();
        $userId = auth()->id();
        $thread = create('App\Thread', ['user_id' => $userId]);

        $this->assertDatabaseHas('activities', [
            'user_id' => $userId,
            'type' => 'created_thread',
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread),
        ]);

        $activity = Activity::first();

        //test the relationship between the activity and the subject
        $this->assertEquals($activity->subject->id, $thread->id);
    }

    public function test_activity_is_recorded_when_reply_is_created()
    {
        $this->signIn();
        $userId = auth()->id();
        $reply = create('App\Reply', ['user_id' => $userId]);

        $this->assertDatabaseHas('activities', [
            'user_id' => $userId,
            'type' => 'created_reply',
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply),
        ]);

        $activity = Activity::first();

        //test the relationship between the activity and the subject
        $this->assertEquals($activity->subject->id, $reply->id);
    }

    public function test_it_can_fetch_feed_of_any_user()
    {
        $this->signIn();
        $userId = auth()->id();

        create('App\Thread', ['user_id' => $userId], 2);
        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);
   

        $feed = Activity::feed(auth()->user());

        //test the relationship between the activity and the subject
        $this->assertTrue($feed->keys()->contains(Carbon::now()->format('d-m-Y')));
        $this->assertTrue($feed->keys()->contains(Carbon::now()->subWeek()->format('d-m-Y')));
    }
}
