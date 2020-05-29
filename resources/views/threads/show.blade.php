@extends('layouts.app')

@section('header')
<link href="{{ asset('css/tribute.css') }}" rel="stylesheet">
@endsection

@section('content')
<thread-view :thread="{{$thread}}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">

                @include('threads._question')

                <br>
                
                <replies @removed="repliesCount--" @added="repliesCount++"></replies>

                <!-- Will use <replies> component instead -->
                {{-- @foreach($replies as $reply)
                     @include('threads.reply')
                     @endforeach --}}

                <!-- Will use frontend pagination instead -->
                {{-- {{$replies->links()}} --}}


            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <article>
                            <p>
                                This thread was published {{$thread->created_at->diffForHumans()}} by
                                <a href="{{route('profile', $thread->creator)}}">{{$thread->creator->name}}</a>
                                and currently has <span v-text="repliesCount"></span> {{Str::plural('comment', $thread->replies_count)}}.
                            </p>

                            <p v-if="signedIn">
                                <subscribe-button :active="{{json_encode($thread->isSubscribedTo)}}"></subscribe-button>
                                <button class="btn btn-outline-danger" v-if="authorize('isAdmin')  && !locked" v-cloak @click="lockThread">Lock</button>
                                <button class="btn btn-danger" v-if="authorize('isAdmin') && locked" v-cloak @click="unlockThread">Unlock</button>
                            </p>
                        </article>
                    </div>
                </div>
            </div>

        </div>
    </div>
</thread-view>
@endsection