@extends('layout.root')

@section('body')

    <main class="p-2">
        <video-grid>
            @foreach ($videos as $video)
                <a class="video-grid-item" href="{{ config('app.url') }}/watch?id={{ $video->getId() }}">
                    <img src="{{ config('app.url') }}/api/thumbnail?id={{ $video->thumbnail->id }}" style="width: 100%;">
                    <div class="info">
                        <p class="title">{{ $video->title }}</p>
                    </div>
                </a>
            @endforeach
        </video-grid>
    </main>
@endsection