@extends('layout.root')

@section('body')
    <main class="d-flex flex-column align-items-center gap-2 p-2">
        <video-grid class="w-100">
            @foreach ($videos as $video)
                @include('modules.video', [
                    "id" => $video->getId(),
                    "title" => $video->title,
                    "thumbnailId" => $video->thumbnail_id,
                    "channelName" => $video->owner->name,
                    "channelId" => $video->owner->id,
                    "views" => $video->views,
                    "timeAgo" => $video->getTimeAgo(),
                    "isNew" => $video->isNew(),
                    "duration" => $video->shortDuration(),
                    "isFromYoutube" => $video->isFromYoutube()
                ])
            @endforeach
        </video-grid>
    </main>
@endsection