<?php

namespace App;

use App\Events\ThreadHasNewReply;
use Illuminate\Database\Eloquent\Model;
use App\Traits\RecordsActivity;
use Illuminate\Support\Str;

class Thread extends Model
{
    protected $guarded = [];
    protected $with = ['creator', 'channel'];
    protected $appends = ['isSubscribedTo'];

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
        self::deleting(function ($thread) {
            // https://laravel-news.com/higher-order-messaging
            // This will invoke delete on each reply associated, which will invoke activities deletion on thread&reply
            $thread->replies->each->delete();
        });
       
        self::created(function ($thread) {
            $thread->update(['slug' => $thread->title]);
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function path()
    {
        return url("/threads/{$this->channel->slug}/$this->slug");
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
        $reply = $this->replies()->create($reply);
        
        event(new ThreadHasNewReply($this, $reply));
  
        return $reply;
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id(),
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function isSubscribedTo(){
        return $this->getIsSubscribedToAttribute();
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
        ->where('user_id', auth()->id())
        ->exists();
    }

    public function hasUpdadesFor($user)
    {
        $key = $user->visitedThreadCacheKey($this);

        return $this->updated_at > cache($key);
    }

    public function visits()
    {
        return new Visits($this);
    }

    public function setSlugAttribute($value)
    {
        if(self::whereSlug($slug = Str::slug($value))->exists()){
            $slug = "{$slug}-{$this->id}";
        }
        $this->attributes['slug'] = $slug;
    }

    public function markBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
    }
}
