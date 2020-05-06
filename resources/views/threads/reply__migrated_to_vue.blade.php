<!-- : will treat it as JSON -->
<reply :data="{{$reply}}" inline-template v-cloak>
    <div id="reply-{{$reply->id}}" class="card mb-3">
        <div class="card-header">
            <div class="level">
                <div class="flex">
                    <a href="{{route('profile', $reply->owner)}}">{{$reply->owner->name}}</a> replied {{$reply->created_at->diffForHumans()}}
                </div>

                @auth
                <favorite :reply="{{$reply}}"></favorite>
                <!-- Added Vue component instead -->
                <!-- <form action="{{url('replies/' .$reply->id . '/favorites')}}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm" {{$reply->isFavorited() ? 'disabled':''}}>
                        {{$reply->favorites_count}} {{Str::plural('favorite', $reply->favorites_count)}}
                    </button>
                </form> -->
                @endauth
            </div>
        </div>
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea name="body" id="body" class="form-control" v-model="body"></textarea>
                </div>
            </div>
            <div v-else v-text="body"></div>
        </div>
        @can('update', $reply)
        <div class="card-footer">
            <div class="level" v-if="editing">
                <button class="btn btn-primary" @click="update">Update</button>
                <button class="btn btn-link" @click="editing = false">Cancel</button>
            </div>

            <div class="level" v-else>
                <button class="btn btn-primary btn-sm mr-3" @click='editing = true'>Edit reply</button>

                <button class="btn btn-danger btn-sm" @click='destroy'>Delete reply</button>

                <!-- We are going to use Vue and Axios instead. see  @click='destroy' above -->

                <!-- <form action="{{url('/replies/'. $reply->id)}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete reply</button>
                </form> -->
            </div>


        </div>
        @endcan
    </div>
</reply>