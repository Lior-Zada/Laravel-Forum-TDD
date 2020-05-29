<!-- Editing the question -->
<div class="card" v-if="editing" v-cloak>

    <div class="card-header">
        <input id="title" type="text" class="form-control" v-model="form.title">
    </div>

    <div class="card-body">
        <textarea rows="5" id="body" class="form-control" v-model="form.body"></textarea>
    </div>

    <div class="card-footer">
        <div class="level">
            <button class="btn btn-primary btn-sm" @click="update" type="button">Update</button>
            <button class="btn btn-link" @click="resetForm" type="button">Cancel</button>
            @can('update', $thread)
            <form action="{{$thread->path()}}" method="POST" class="ml-auto">
                @csrf
                <!-- HTML forms cannot send PUT PATCH OR DELETE, so we use this to tell laravel to alter the method. -->
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Delete thread</button>
            </form>
            @endcan
        </div>

    </div>
</div>

<!-- Viewing the question -->
<div class="card" v-if="! editing">
    <div class="card-header">
        <div class="level">
            <div class="flex">
                <a href="{{route('profile', $thread->creator)}}">
                    <img class="mr-1" src="{{$thread->creator->avatar_path}}" alt="{{$thread->creator->name}}" width="35" height="35">
                    {{$thread->creator->name}}
                </a>
                posted:
            </div>

        </div>

    </div>
    <div class="card-body">
        <article>
            <h4 v-text="title"></h4>
            <div class="body" v-text="body"></div>
        </article>
    </div>

    <div class="card-footer" v-if="authorize('owns', thread)">
        <button class="btn btn-primary btn-sm mr-3" @click="editing = true">Edit thread</button>
    </div>
</div>