<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Stevebauman\Purify\Facades\Purify;

class Reply extends Model
{
    use Traits\Favorable;
    use Traits\RecordsActivity;

    protected $guarded = [];

    // Whenever you cast to an array\json, append this custom attribute to it.
    // "favoritesCount" is available in blade when used with php, but when casted to json to pass to Vue component, its not available. 
    protected $appends = ['favoritesCount', 'isFavorited', 'isBest'];

    // This will add these relationships to the reply queries. Always. 
    // We can also use the boot to addGlobalScope to silence it for certain queries. 
    protected $with = ['owner', 'favorites', 'thread'];

    protected static function boot()
    {
        parent::boot();

        self::deleting(function ($reply) {
            $reply->favorites->each->delete();
            $reply->thread->decrement('replies_count');
            // $reply->thread->update(['best_reply_id' => null]); // taken the database approach, see create_threads migration
        });

        self::created(function($reply){
            $reply->thread->increment('replies_count');
        });
    }

    public function path()
    {
        return url($this->thread->path() . "#reply-{$this->id}");
    }

    public function owner()
    {
        // since the function name is owner, must explict the forien key to be different -> user_id
        return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
    
    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function mentionedUsers()
    {
        preg_match_all("/\@([\w\-]+)/", $this->body, $matches);
        return $matches[1];
    }

    public function setBodyAttribute($body){
        $this->attributes['body'] = htmlspecialchars_decode( preg_replace("/\@([\w\-]+)/", '<a href="/profiles/$1">$0</a>', $body));
    }

    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    public function getIsBestAttribute() : bool
    {
        return $this->isBest();
    }

    public function getBodyAttribute()
    {
        return Purify::clean( $this->attributes['body']);
    }
}
