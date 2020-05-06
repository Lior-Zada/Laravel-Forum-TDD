@component('profiles.activities.activity')
@slot('heading')
{{$profileUser->name}}
posted:
<a href="{{$activity->subject->path()}}">
    {{$activity->subject->title}}
</a>
{{$activity->subject->created_at->diffForHumans()}}
@endslot

@slot('body')
{{$activity->subject->body}}
@endslot

@endcomponent