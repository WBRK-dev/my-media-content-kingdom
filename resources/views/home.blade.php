@extends('layout.root')

@section('body')
    <main class="d-flex flex-column align-items-center gap-2 p-2">
        <div class="ad-banner debugborder">
            <p>This is a placeholder for an ad banner</p>
        </div>
        <video-grid class="w-100">
            @foreach ($videos as $video)
                {{-- <a class="placeholder-video" href="{{ config('app.url') }}/watch?id={{ $video->getId() }}">
                    <img src="{{ config('app.url') }}/api/thumbnail?id={{ $video->thumbnail->id }}" style="width: 100%;">
                    <p> {{ $video->title }}</p>
                </a> --}}
                <a href="{{ config('app.url') }}/watch?id={{ $video->getId() }}" class="video-grid-item">
                    <img src="{{ config('app.url') }}/api/thumbnail?id={{ $video->thumbnail->id }}" style="width: 100%;">
                    <div class="info">
                        <p class="title">{{ $video->title }}</p>
                        <div class="d-flex justify-content-between">
                            <div>
                                @if ($video->getViews() == 1)
                                    <div>{{$video->getViews()}} view</div>
                                @else
                                    <div>{{$video->getViews()}} views</div>
                                @endif
                                <div>{{$video->owner->name}}</div>
                            </div>
                            <div>
                                {{$video->getTimeAgo()}}
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </video-grid>
    </main>
@endsection
