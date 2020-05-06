<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use  App\Traits\RecordsActivity;

class Thread extends Model
{
    protected $guarded = [];
    protected $with = ['creator', 'channel'];

    use RecordsActivity;

    // laravel knows to trigger automatically
    protected static function boot()
    {
        parent::boot();

        // its a query scope, that is automatically applied to all of the queries.
        // If you want to disable it for your query, use "->withoutGlobalScopes()"
        // This is now stored at the database instead
        // self::addGlobalScope('replyCount', function($builder){
        //     return $builder->withCount('replies');
        // });

        // Register a model event that occurs on deleting, when you delete a thread, also delete it's associated replies
        self::deleting(function($thread){
            // https://laravel-news.com/higher-order-messaging
            // This will invoke delete on each reply associated, which will invoke activities deletion on thread&reply
            $thread->replies->each->delete();
        });
    }

    public function path()
    {
        return url("/threads/{$this->channel->slug}/$this->id");
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function addReply($reply)
    {
        // $this->increment('replies_count'); // It is an option, but we'll use model event.
        return $this->replies()->create($reply);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }
}
