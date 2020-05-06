<div class="card mb-3">

    <div class="card-header">
        <div class="level">
            <div class="flex">
                {{$heading}}
            </div>
        </div>
    </div>
    <div class="card-body">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif
        <article>

            <div class="body">
                {{$body}}
            </div>
        </article>
    </div>

</div>