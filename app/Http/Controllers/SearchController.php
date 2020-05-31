<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Trending;

class SearchController extends Controller
{
    public function show(Trending $trending)
    {
        
        if (request()->expectsJson()){
            return Thread::search(request('query'))->paginate(25);
        }

        return view('Threads.search', [
            'trending' => $trending->get(),
            'config' => json_encode(['appId' => config('scout.algolia.id'), 'searchKey' => config('scout.algolia.searchKey') ])
        ]);
    }
}
