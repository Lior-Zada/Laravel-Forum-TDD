@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @forelse($threads as $thread)

            <div class="card mb-3">
                <div class="card-header">
                    <div class="level">
                        <h4 class="flex">
                            <a href="{{$thread->path()}}">
                                @if($thread->hasUpdadesFor(auth()->user()))
                                <strong>
                                    {{$thread->title}}
                                </strong>
                                @else
                                {{$thread->title}}
                                @endif
                            </a>
                        </h4>

                        <a href="{{url($thread->path())}}">{{$thread->replies_count}} {{Str::plural('reply', $thread->replies_count)}}</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div class="body">{{$thread->body}}</div>

                    <hr>

                </div>
            </div>
            @empty
            <p>There are no results at this time.</p>
            @endforelse

        </div>
    </div>
</div>
@endsection