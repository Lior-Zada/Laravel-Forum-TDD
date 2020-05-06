<?php

namespace App\Traits;
use App\Favorite;

trait Favorable
{
    protected static function bootFavorable()
    {
        self::deleting(function($model){
            $model->favorites->each->delete();   
        });
    }

     public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        // Eloquent will automatically assign favorited_id\type, because of the morphMany relationship connection.
        if(! $this->isFavorited()){
            return $this->favorites()->create(['user_id' => auth()->id()]);
        }
        
         // return Favorite::create([
        //     'user_id' => auth()->id(),
            // 'favorited_id' => $reply->id,
            // 'favorited_type' => get_class($reply)
        // ]);
    }

    public function unfavorite()
    {
        // This is an SQL query, and will not invoke the delete activity, because there is no model involved, and it is a MODEL event.
        // $this->favorites()->where('user_id' , auth()->id())->delete();

        // To trigger the model event, use the ->get(), to get a collection through the model, then the delete the
        // model collection, and the delete activity will work.
        $this->favorites()->where('user_id', auth()->id())->get()->each->delete();

    }

    public function isFavorited() : bool
    { 
        return (bool) $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavoritedAttribute() : bool
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute() : int
    {
        return $this->favorites->count();
    }
}
