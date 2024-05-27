@extends('layout.root')

@section('body')
    <main>
        <div class="videos-grid">
            @foreach ($videos as $video)
            <div>
                <a class="placeholder-video" href="{{ config('app.url') }}/watch?id={{ $video->getId() }}">
                    <img src="{{ config('app.url') }}/api/thumbnail?id={{ $video->thumbnail->id }}" style="width: 100%;">
                    <p> {{ $video->title }}</p>
                </a>
                @permission('video-remove')
                    <form method="post" action="{{ config('app.url') }}/delete/{{ $video->id }}">
                        @csrf
                        @method('DELETE')
                        <button>Delete</button>
                    </form>
                @endpermission
            </div>
            @endforeach
        </div>
    </main>
@endsection
