@extends('layout.root')

@section('title', 'Home - ')

@section('body')
    <main class="d-flex flex-column align-items-center gap-2 p-2 pt-0">
        <video-grid class="w-100">
            @foreach ($videos as $video)
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

        @if ($videos->hasMorePages())
            <div class="d-flex justify-content-center">
                <button class="load-more" onclick="loadVideos(this)" data-page="2">Load More</button>
            </div>
        @endif

    </main>
@endsection

@section("head")
    
    <script>

        async function loadVideos(btn) {
            const response = await (await fetch("{{config('app.url')}}/api/home/videos?page=" + btn.getAttribute("data-page"))).json();
            
            if (response.hasNextPage) {
                btn.setAttribute("data-page", parseInt(btn.getAttribute("data-page")) + 1);
            } else {
                btn.parentNode.classList.add("d-none");
            }
            
            document.querySelector("video-grid").innerHTML += response.videos;
        }

    </script>

@endsection