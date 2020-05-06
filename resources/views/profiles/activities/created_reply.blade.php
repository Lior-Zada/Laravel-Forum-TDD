@component('profiles.activities.activity')
@slot('heading')
{{$profileUser->name}}
replied to thread:
<a href="{{$activity->subject->thread->path()}}">
    {{$activity->subject->thread->title}}
</a>
{{$activity->subject->created_at->diffForHumans()}}
@endslot

@slot('body')
{{$activity->subject->body}}
@endslot

@endcomponent