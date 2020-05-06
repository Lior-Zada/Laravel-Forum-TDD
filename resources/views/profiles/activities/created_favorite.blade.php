@component('profiles.activities.activity')
@slot('heading')
{{$profileUser->name}}
<a href="{{$activity->subject->favorited->path()}}">
favorited a reply
</a>
{{$activity->subject->created_at->diffForHumans()}}
@endslot

@slot('body')
{{$activity->subject->favorited->body}}
@endslot

@endcomponent