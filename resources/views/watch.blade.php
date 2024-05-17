@extends('layout.root')

@section('body')
    
    <video controls muted></video>

    

@endsection

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/hls.js@1.5.8/dist/hls.min.js"></script>
    <script>
        window.onload = ()=> {
            var video = document.querySelector('video');
            var videoSrc = '{{ config("app.url") }}/watch/{{$video->id}}/index.m3u8';
            if (Hls.isSupported()) {
                const config = {
                    startPosition: 0
                }
                var hls = new Hls(config);
                hls.loadSource(videoSrc);
                hls.attachMedia(video);
                video.play();
            }
            // HLS.js is not supported on platforms that do not have Media Source
            // Extensions (MSE) enabled.
            //
            // When the browser has built-in HLS support (check using `canPlayType`),
            // we can provide an HLS manifest (i.e. .m3u8 URL) directly to the video
            // element through the `src` property. This is using the built-in support
            // of the plain video element, without using HLS.js.
            else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = videoSrc;
            }
        }
    </script>
@endsection