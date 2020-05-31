@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('threads._list')

            {{$threads->links()}}
        </div>

        <div class="col-md-4">
        <div class="card mb-3">
                <div class="card-header">
                    Search
                </div>

                <div class="card-body">
                    <form action="{{route('search.show')}}" method="GET">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search..." name="query">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm">Search</button>
                    </div>
                    </form>
                </div>
            </div>
            @if(count($trending))
            <div class="card">
                <div class="card-header">
                    Trending Threads
                </div>

                <div class="card-body">
                    <ul class="list-group">
                        @foreach($trending as $trend)
                        <li class="list-group-item">
                            <a href="{{$trend->path}}">{{$trend->title}}</a>
                        </li>
                        @endforeach
                    </ul>

                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection