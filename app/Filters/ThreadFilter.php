<?php

namespace App\Filters;


use App\User;
use App\Filters\Filter;


class ThreadFilter extends Filter{


    protected $filters = ['by', 'popular', 'unanswered'];

    /**
     * Filter the query by a given username
     * @param String $username
     * @return Builder 
     */
    public function by($username)
    {
        $user = User::where('name',$username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    
    /**
     * Filter the query according to the most popular threads.
     * @return Builder 
     */
    public function popular()
    {
        return $this->builder->reorder('replies_count', 'desc');
    }

    /**
     * Filter the query to receive threads that are unanswered.
     * @return Builder 
     */
    public function unanswered()
    {
        return $this->builder->where('replies_count', 0);
    }

}