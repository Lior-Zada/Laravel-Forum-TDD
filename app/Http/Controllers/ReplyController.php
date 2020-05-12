<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Reply;
use App\Thread;
use Exception;

class ReplyController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    // Dependency injection of CreatePostForm will decide if to approve this request.
    // Laravel knows to use it automaticaly when its injected.
    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {
        return $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id(),
        ])->load('owner'); // must eagerLoad owner manually since we're returning json and not a redirect
       
        // If we're using a regular form.
        // return back()->with('flash', 'Your reply was saved.');
    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted!']);
        }

        return back();
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        request()->validate(['body' => 'required|spamfree']);
        $reply->update(['body' => request('body')]);

        // when using Axios we're sending json..
        if (request()->expectsJson()) {
            return response(['status' => 'Reply updated!']);
        }

        // when request arrives from form...
        // return back();
    }

    public function validateReply()
    {
    }
}
