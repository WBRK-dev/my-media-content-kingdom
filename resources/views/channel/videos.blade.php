@extends("channel.root")

@section("channel-content")
    
    <video-grid class="w-100">
        @foreach ($data["videos"] as $video)
            {{-- <video-grid-item class="{{ $video->isFromYoutube() ? "youtube" : "" }}">
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
            </video-grid-item> --}}
            @include('modules.video', [
                    "id" => $video->getId(),
                    "title" => $video->title,
                    "thumbnailId" => $video->thumbnail_id,
                    "channelName" => $video->owner->name,
                    "channelId" => $video->owner->id,
                    "views" => $video->getViews(),
                    "timeAgo" => $video->getTimeAgo(),
                    "isNew" => $video->isNew(),
                    "duration" => $video->shortDuration(),
                    "isFromYoutube" => $video->isFromYoutube()
                ])
        @endforeach
    </video-grid>

    @if ($data["hasNextPage"])
        <div class="d-flex justify-content-center">
            <button class="load-more" onclick="loadVideos(this)" data-page="2">Load More Videos</button>
        </div>
    @endif

@endsection

@section("channel-head")
    
    <script>

        async function loadVideos(btn) {
            const response = await (await fetch("{{config('app.url')}}/api/channel/videos?id={{$channel->id}}&page=" + btn.getAttribute("data-page"))).json();
            
            if (response.hasNextPage) {
                btn.setAttribute("data-page", parseInt(btn.getAttribute("data-page")) + 1);
            } else {
                btn.parentNode.classList.add("d-none");
            }
            
            document.querySelector("video-grid").innerHTML += response.videos;
        }

    </script>

@endsection