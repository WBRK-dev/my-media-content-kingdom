@extends('layout.root')

@section('body')
    <main class="d-flex f-col align-c debugborder">
        <div class="search-bar d-flex justify-c debugborder-round">
            <p>This is a placeholder for the search bar</p>
            <span class="material-symbols-outlined" style="font-size: 35px; display: flex; align-items: center">search</span>
        </div>
        <div class="ad-banner debugborder">
            <p>This is a placeholder for an ad banner</p>
        </div>
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
