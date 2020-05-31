@forelse($threads as $thread)
<div class="card mb-3">
    <div class="card-header">
        <div class="level">
            <div class="flex">
                <h4>
                    <a href="{{$thread->path()}}">
                        @if(auth()->check() && $thread->hasUpdadesFor(auth()->user()))
                        <strong>
                            {{$thread->title}}
                        </strong>
                        @else
                        {{$thread->title}}
                        @endif
                    </a>
                </h4>
                <h5>Created by: <a href="{{route('profile', $thread->creator)}}">{{$thread->creator->name}}</a></h5>
            </div>
            <a href="{{url($thread->path())}}">{{$thread->replies_count}} {{Str::plural('reply', $thread->replies_count)}}</a>
        </div>
    </div>

    <div class="card-body">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif
        <div class="body">{!! $thread->body !!}</div>

        <hr>

    </div>

    <div class="card-footer">
        {{$thread->visits()->count()}} {{Str::plural('Visit', $thread->visits()->count())}}
    </div>
</div>
@empty
<p>There are no results at this time.</p>
@endforelse