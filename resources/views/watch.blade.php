@extends('layout.root')

@section('body')
    
    <div style="display: flex; justify-content: center;"><div style="width: min(100%, 1000px);">

        <video controls muted style="display: block; width: 100%; aspect-ratio: 16/9; background-color: #474747;"></video>
    
        <select onchange="updateResolution(this)" style="display: block;"></select>

        <div class="watch-info">

            <div>{{$video->title}}</div>

            @if (date('d-m-Y') == $video->created_at->format('d-m-Y'))
                Today
            @else
                <div>{{$video->created_at->format('j F Y')}}</div>
            @endif

        </div>

        <div class="watch-info">

            @if ($video->getViews() == 1)
                <div>{{$video->getViews()}} view</div>
            @else
                <div>{{$video->getViews()}} views</div>
            @endif

            <div>{{$video->owner->name}}</div>

        </div>
        <div class="d-flex gap-2">
            <div class="d-flex gap-1">
                <label for="chkbLike" id="lbLike">Likes:</label>
                <div id="likeAmount">{{$video->getLikes()}}</div>
                @if ($likestatus == 1)
                    <input type="checkbox" id="chkbLike" checked>
                @else
                    <input type="checkbox" id="chkbLike">
                @endif
            </div>
            <div class="d-flex gap-1">
                <label for="chkbDislike">Dislikes:</label>
                <div id="dislikeAmount">{{$video->getDislikes()}}</div>
                @if ($likestatus === 0)
                    <input type="checkbox" id="chkbDislike" checked>                   
                @else
                    <input type="checkbox" id="chkbDislike">
                @endif
            </div>
        </div>

    </div></div>


@endsection

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/hls.js@1.5.8/dist/hls.min.js"></script>
    <script>
        let hls;
        document.addEventListener("DOMContentLoaded", () => {
            var video = document.querySelector('video');
            var videoSrc = '{{ config("app.url") }}/watch/{{$video->id}}/index.m3u8';
            if (Hls.isSupported()) {

                const config = { startPosition: 0 };
                hls = new Hls(config);
                hls.loadSource(videoSrc);
                hls.attachMedia(video);
                video.play();

                hls.on(Hls.Events.MANIFEST_PARSED, function (event, data) {
                    let resolutionSelectorElem = document.querySelector("select");
                    resolutionSelectorElem.innerHTML = "";
                    for (let i = 0; i < hls.levels.length; i++) {
                        resolutionSelectorElem.innerHTML += `<option value="${i}">${hls.levels[i].height}p</option>`;
                    }
                });

            } else if (video.canPlayType('application/vnd.apple.mpegurl')) { video.src = videoSrc; video.play(); }


            let likeElement = document.getElementById("chkbLike");
            let dislikeElement = document.getElementById("chkbDislike");
            
            let likeAmountElem = document.getElementById("likeAmount");
            let dislikeAmountElem = document.getElementById("dislikeAmount");            

            likeElement.addEventListener('change', function() {
                if (this.checked) {
                    if (dislikeElement.checked) {
                        dislikeAmountElem.innerHTML = ((parseInt(dislikeAmountElem.innerHTML))-1);
                        dislikeElement.checked = false;
                    }
                    likeAmountElem.innerHTML = ((parseInt(likeAmountElem.innerHTML))+1);
                    fetch('{{config("app.url")}}/api/watch/liked?id={{ $video->getId() }}');
                }
                else if (!this.checked && !dislikeElement.checked) {
                    likeAmountElem.innerHTML = ((parseInt(likeAmountElem.innerHTML))-1);
                    fetch('{{config("app.url")}}/api/watch/like_rem_row?id={{ $video->getId() }}');
                }
            });

            dislikeElement.addEventListener('change', function() {
                if (this.checked) {
                    if (likeElement.checked) {
                        likeAmountElem.innerHTML = ((parseInt(likeAmountElem.innerHTML))-1);
                        likeElement.checked = false;
                    }
                    dislikeAmountElem.innerHTML = ((parseInt(dislikeAmountElem.innerHTML))+1);
                    fetch('{{config("app.url")}}/api/watch/disliked?id={{ $video->getId() }}');
                } 
                else if (!this.checked && !likeElement.checked) {
                    dislikeAmountElem.innerHTML = ((parseInt(dislikeAmountElem.innerHTML))-1);
                    fetch('{{config("app.url")}}/api/watch/like_rem_row?id={{ $video->getId() }}');
                }            
            });
    
        });

        function updateResolution(elem) {
            hls.currentLevel = Number(elem.value);
        }
        
    </script>
@endsection