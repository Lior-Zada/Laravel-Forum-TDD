<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_is_prepared_when_a_subscribed_thread_receives_a_new_reply_that_is_not_the_current_user()
    {
        $this->signIn();

        $thread = create('App\Thread')->subscribe();

        
        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(), 
            'body' => 'Reply added!'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Reply added!'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    public function test_a_user_can_fetch_his_unread_notifications()
    {
        $this->signIn();

        $thread = create('App\Thread')->subscribe();
        
        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Reply added!'
        ]);

        $user = auth()->user();
            $this->withoutExceptionHandling();
        $response = $this->getJson("/profiles/$user->name/notifications")->json();

        $this->assertCount(1, $response);

    }

    public function test_a_user_can_mark_his_notifications_as_read()
    {
        $this->signIn();

        $thread = create('App\Thread')->subscribe();

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Testing zone'
        ]);

        $user = auth()->user();

        $this->assertCount(1, $user->unreadNotifications);
        
        $notificationId = $user->unreadNotifications->first()->id;
        
        $this->delete("/profiles/$user->name/notifications/$notificationId");
        
        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
