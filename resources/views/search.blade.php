@extends('layout.root')

@section('body')
    <main>
        <div class="videos-grid">
            @foreach ($videos as $video)
            <div class="video-grid-item">
                <a class="video-grid-item" href="{{ config('app.url') }}/watch?id={{ $video->getId() }}">
                    <img src="{{ config('app.url') }}/api/thumbnail?id={{ $video->thumbnail->id }}" style="width: 100%;">
                    <div class="info">
                        <p class="title">{{ $video->title }}</p>
                        @if ($video->getViews() == 1)
                            <div>{{ $video->getViews() }} view</div>
                        @else
                            <div>{{ $video->getViews() }} views</div>
                        @endif
                    </div>
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
