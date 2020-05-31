@extends('layouts.app')

@section('content')
<search-view :data="{{$config}}" inline-template v-cloak>
    <ais-instant-search :routing="routing" :search-client="searchClient" index-name="threads">


        <div class="container">
            <div class="row justify-content-center">

                <div class="col-md-8">
                    <ais-hits>
                        <div slot="item" slot-scope="{ item }">
                            <a :href="item.path">
                                @{{item.title}}
                                <ais-highlight attribute="title" :hit="item" />
                            </a>
                        </div>
                    </ais-hits>
                    <ais-configure :hits-per-page.camel="6" />
                    <ais-pagination />
                </div>

                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            Search
                        </div>
                        <div class="right-panel">
                            <ais-search-box auto-focus="true" />
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header">
                            Filter by channel
                        </div>
                        <div class="left-panel">
                            <ais-clear-refinements />
                            <ais-refinement-list attribute="channel.name" />
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
    </ais-instant-search>
    </template>
    @endsection