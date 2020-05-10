<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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


    public function store($channelId, Thread $thread)
    {
        // $this->authorize('create', new Reply);
        if (Gate::denies('create', new Reply)) {
            return response('You are posting too many replies, please take a break.', 422);
        }
        
        try {
            $this->validate(request(), ['body' => 'required|spamfree']);

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id(),
            ]);
        } catch (Exception $e) {
            // 422 Unprocessable Entity
            return response('Sorry, your reply could not be saved right now.', 422);
        }

        if (request()->expectsJson()) {
            // must eagerLoad owner manually in this case
            return $reply->load('owner');
        }

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

        try {
            request()->validate(['body' => 'required|spamfree']);

            $reply->update(['body' => request('body')]);
        } catch (Exception $e) {
            // 422 Unprocessable Entity
            return response('Sorry, your reply could not be saved right now.', 422);
        }

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
