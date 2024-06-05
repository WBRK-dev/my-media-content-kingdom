@extends('layout.root')

@section('body')
    <main>
        <video-grid class="w-100 d-flex flex-column p-4" style="box-sizing: border-box">
            @foreach ($videos as $video)
                <div class="d-flex align-items-center">
                    <a href="{{ config('app.url') }}/watch?id={{ $video->video->getId() }}" class="reported-video-grid-item d-flex align-items-center gap-4">
                        <img src="{{ config('app.url') }}/api/thumbnail?id={{ $video->video->thumbnail->id }}" style="width: 10%">
                        <div>Title: {{$video->video->title}}</div>
                        <div>Channel: {{$video->video->owner->name}}</div>
                        <div>Reporter: {{$video->user->name}}</div>
                        <div>Reason: {{config('app.report_reasons')[$video->reason_id]}}</div>
                    </a>
                    <div style="margin-left: auto;" class="flex-shrink-0">
                        <button onclick="">Accept</button>
                        <button onclick="">Deny</button>
                    </div>
                </div>
            @endforeach
        </video-grid>
    </main>
@endsection

@section('head')
    <script>
        
    </script>
@endsection