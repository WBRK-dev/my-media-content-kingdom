<video-grid-item class="{{ $isFromYoutube ? "youtube" : "" }}">
    <a href="{{ config('app.url') }}/watch?id={{ $id }}" class="img-wrapper">
        <img src="{{ config('app.url') }}/api/thumbnail?id={{ $thumbnailId }}" class="video-thumbnail" style="width: 100%;">
        <p class="tag">{{ $duration }}</p>
    </a>
    <div class="info">
        <a class="title mb-2" href="{{ config('app.url') }}/watch?id={{ $id }}">{{ $title  }}</a>
        <div class="d-flex justify-content-between mt-auto">
            <div>
                <a href="{{config("app.url")}}/channel/{{$channelId}}">{{$channelName}}</a>
                <div>
                    {{$views}}

                    @if ($views == 1)
                        view
                    @else
                        views 
                    @endif

                    &#x2022; 
                    
                    {{$timeAgo}}
                    
                    @if ($isNew)
                        <div class="new-badge">NEW</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</video-grid-item>