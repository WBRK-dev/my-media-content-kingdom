@extends('layout.root')

@section('body')
    <main class="d-flex flex-column align-items-center gap-2 p-2">
        <div class="ad-banner debugborder">
            <p>This is a placeholder for an ad banner</p>
        </div>
        <video-grid class="w-100">
            @foreach ($videos as $video)
                <a href="{{ config('app.url') }}/watch?id={{ $video->getId() }}" class="video-grid-item">
                    <img src="{{ config('app.url') }}/api/thumbnail?id={{ $video->thumbnail->id }}" style="width: 100%;">
                    <div class="info">
                        <p class="title mb-2">{{ $video->title  }}</p>
                        <div class="d-flex justify-content-between">
                            <div>
                                <div>{{$video->owner->name}}</div>
                                <div>
                                    {{$video->getViews()}}

                                    @if ($video->getViews() == 1)
                                        view
                                    @else
                                        views 
                                    @endif

                                    &#x2022; {{$video->getTimeAgo()}}
                                    
                                    @if ($video->isNew())
                                        <div class="new-badge">NEW</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </video-grid>
    </main>
@endsection
