@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1>
                        {{$profileUser->name}}
                        <small>{{__('Joined')}} {{$profileUser->created_at->diffForHumans()}}</small>
                    </h1>
                </div>

                <div class="card-body">
                    @forelse($activities as $date => $activity)
                        <h4>{{$date}}</h4>
                        @foreach($activity as $record)
                            @if(view()->exists("profiles.activities.{$record->type}"))
                                @include("profiles.activities.{$record->type}", ['activity' => $record])
                            @endif
                        @endforeach
                    @empty
                        <p>No activities for this profile yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection