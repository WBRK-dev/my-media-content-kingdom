@extends('layout.root')

@section('body')

    <form action="./" style="position: fixed; top: .5rem; left: .5rem;"><button>Home</button></form>
    
    <div style="display: flex; justify-content: center;"><div style="width: min(100%, 1000px);">

        <video controls muted style="display: block; width: 100%;"></video>
    
        <select onchange="updateResolution(this)" style="display: block;"></select>

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
        });

        function updateResolution(elem) {
            hls.currentLevel = Number(elem.value);
        }
    </script>
@endsection