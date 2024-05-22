@extends('layout.root')

@section('body')

    <main>
        <div class="videos-grid">
            @foreach ($videos as $video)
                <a class="placeholder-video" href="{{ config('app.url') }}/watch?id={{ $video->getId() }}">
                    <img src="{{ config('app.url') }}/api/thumbnail?id={{ $video->thumbnail->id }}" style="width: 100%;">
                    <p> {{ $video->title }}</p>
                </a>
            @endforeach
        </div>
    </main>
@endsection