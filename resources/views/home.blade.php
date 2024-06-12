@extends('layout.root')

@section('body')
    <main class="d-flex flex-column align-items-center gap-2 p-2">
        <video-grid class="w-100">
            @foreach ($videos as $video)
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
                        @permission('video-remove')
                        <form method="post" action="{{ config('app.url') }}/delete/{{ $video->id }}">
                            @csrf
                            @method('DELETE')
                            <button>Delete</button>
                        </form>
                        @endpermission
                    </a>
                </video-grid-item>
            @endforeach
        </video-grid>
    </main>
@endsection