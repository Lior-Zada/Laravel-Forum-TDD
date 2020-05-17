@extends('layouts.app')

@section('header')
<link href="{{ asset('css/tribute.css') }}" rel="stylesheet">
@endsection

@section('content')
<thread-view :initial-replies-count="{{$thread->replies_count}}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">

                <div class="card">
                    <div class="card-header">
                        <div class="level">
                            <div class="flex">
                                <a href="{{route('profile', $thread->creator)}}">
                                    <img class="mr-1" src="{{$thread->creator->avatar_path}}" alt="{{$thread->creator->name}}" width="35" height="35">
                                    {{$thread->creator->name}}
                                </a>
                                posted:
                            </div>
                            @can('update', $thread)
                            <form action="{{$thread->path()}}" method="POST">
                                @csrf
                                <!-- HTML forms cannot send PUT PATCH OR DELETE, so we use this to tell laravel to alter the method. -->
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete thread</button>
                            </form>
                            @endcan
                        </div>

                    </div>
                    <div class="card-body">
                        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif
                        <article>
                            <h4>{{$thread->title}}</h4>
                            <div class="body">{{$thread->body}}</div>
                        </article>
                    </div>
                </div>

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

                            <p>
                                <subscribe-button :active="{{json_encode($thread->isSubscribedTo)}}"></subscribe-button>
                            </p>
                        </article>
                    </div>
                </div>
            </div>

        </div>
    </div>
</thread-view>
@endsection