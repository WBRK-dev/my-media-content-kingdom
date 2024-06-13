@extends("channel.root")

@section("channel-content")
    
    <div>
        <p class="fs-5 fw-bold mb-1">Recently Uploaded</p>
        <video-grid class="w-100 single-row">
            @foreach ($channel->videos()->orderBy("created_at", "desc")->limit(10)->get() as $video)
                <video-grid-item class="{{ $video->isFromYoutube() ? "youtube" : "" }}">
                    <a href="{{ config('app.url') }}/watch?id={{ $video->getId() }}">
                        <div class="img-wrapper">
                            <img src="{{ config('app.url') }}/api/thumbnail?id={{ $video->thumbnail->id }}" class="video-thumbnail" style="width: 100%;">
                            <p class="tag">{{ $video->shortDuration() }}</p>
                        </div>
                        <div class="info">
                            <p class="title mb-2">{{ $video->title  }}</p>
                            <div class="d-flex justify-content-between mt-auto">
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
                </video-grid-item>
            @endforeach
        </video-grid>
    </div>

    <div>
        <p class="fs-5 fw-bold mb-1">Most Liked</p>
        <video-grid class="w-100 single-row">
            @foreach ($data["most_liked"] as $video)
                <video-grid-item class="{{ $video->isFromYoutube() ? "youtube" : "" }}">
                    <a href="{{ config('app.url') }}/watch?id={{ $video->getId() }}">
                        <div class="img-wrapper">
                            <img src="{{ config('app.url') }}/api/thumbnail?id={{ $video->thumbnail->id }}" class="video-thumbnail" style="width: 100%;">
                            <p class="tag">{{ $video->shortDuration() }}</p>
                        </div>
                        <div class="info">
                            <p class="title mb-2">{{ $video->title  }}</p>
                            <div class="d-flex justify-content-between mt-auto">
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
                </video-grid-item>
            @endforeach
        </video-grid>
    </div>

    <div>
        <p class="fs-5 fw-bold mb-1">Most Viewed</p>
        <video-grid class="w-100 single-row">
            @foreach ($data["most_viewed"] as $video)
                <video-grid-item class="{{ $video->isFromYoutube() ? "youtube" : "" }}">
                    <a href="{{ config('app.url') }}/watch?id={{ $video->getId() }}">
                        <div class="img-wrapper">
                            <img src="{{ config('app.url') }}/api/thumbnail?id={{ $video->thumbnail->id }}" class="video-thumbnail" style="width: 100%;">
                            <p class="tag">{{ $video->shortDuration() }}</p>
                        </div>
                        <div class="info">
                            <p class="title mb-2">{{ $video->title  }}</p>
                            <div class="d-flex justify-content-between mt-auto">
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
                </video-grid-item>
            @endforeach
        </video-grid>
    </div>

@endsection