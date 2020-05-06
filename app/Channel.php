<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    // By default, laravel Route model binding will try to fetch the item according to 'id'
    // We Overload the parent class  'getRouteKeyName' function, so that we can search by the 'slug' and not the primaryKey 'id'.
    // ***** There is a new way to do this, through the web.php -> .../{channel:slug} *****
    // public function getRouteKeyName()
    // {
    //     return 'slug';
    // }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}
