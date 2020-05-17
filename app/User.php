<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar_path',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    public function activity()
    {
        return $this->hasMany(Activity::class)->latest();
    }

    // Store in cache the timestamp that the user read the thread
    public function read($thread)
    {
        cache()
            ->forever(
                $this->visitedThreadCacheKey($thread),
                Carbon::now()
            );
    }

    public function visitedThreadCacheKey($thread)
    {
        return sprintf("users.%s.visits.%s", $this->id, $thread->id);
    }

    public function lastReply()
    {
        // Yes a user "hasMany", but since we only want one, we can use this.
        return $this->hasOne(Reply::class)->latest();
    }

    public function getAvatarPathAttribute($avatar)
    {
        return !$avatar ? asset('storage/avatars/default.jpg') : asset('storage/' . $avatar );
    }
}
