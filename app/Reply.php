<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Reply extends Model
{
    use Traits\Favorable;
    use Traits\RecordsActivity;

    protected $guarded = [];

    // Whenever you cast to an array\json, append this custom attribute to it.
    // "favoritesCount" is available in blade when used with php, but when casted to json to pass to Vue component, its not available. 
    protected $appends = ['favoritesCount', 'isFavorited'];

    // This will add these relationships to the reply queries. Always. 
    // We can also use the boot to addGlobalScope to silence it for certain queries. 
    protected $with = ['owner', 'favorites', 'thread'];

    protected static function boot()
    {
        parent::boot();

        self::deleting(function ($reply) {
            $reply->favorites->each->delete();
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
}
