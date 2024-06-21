@extends("layout.root")

@section("body")
    
    <video-grid class="p-2 pt-0">
        @foreach ($videos as $video)
            <video-grid-item class="{{ $video->isFromYoutube() ? "youtube" : "" }}">
                <div class="img-wrapper">
                    <img src="{{ config('app.url') }}/api/thumbnail?id={{ $video->thumbnail_id }}" class="video-thumbnail" style="width: 100%;">
                    <p class="tag">{{ $video->shortDuration() }}</p>
                </div>
                <div class="info">
                    <p class="title mb-2">{{ $video->title }}</p>
                    <div class="d-flex justify-content-between mt-auto mb-2">
                        <div>
                            <p>{{$video->owner->name}}</p>
                            <div>
                                {{$video->getViews()}}
            
                                @if ($video->getViews() == 1)
                                    view
                                @else
                                    views 
                                @endif
            
                                &#x2022; 
                                
                                {{$video->getTimeAgo()}}
                            </div>
                        </div>
                    </div>
                    <div class="upload-info">
                        <progress-bar style="--width: {{ 100 / ( $video->isFromYoutube() ? 4 : 3 ) * $video->status  }}%;"><p>{{ 100 / ( $video->isFromYoutube() ? 4 : 3 ) * $video->status  }}%</p></progress-bar>
                    </div>
                </div>
            </video-grid-item>
        @endforeach
    </video-grid>

    @if (count($videos) === 0)
        <div class="d-flex justify-content-center"><p class="text-center" style="color: var(--body-secondary-color);">The upload queue is empty.</p></div>
    @endif

@endsection

@section("head")
    
    <style>

        video-grid-item progress-bar {
            display: flex;
            justify-content: center;
            width: 100%;
            background-color: #525252;
            border-radius: .25rem;

            position: relative;
            isolation: isolate;
            overflow: hidden;
        }
        video-grid-item progress-bar::before {
            content: "";
            display: block;
            height: 100%; width: var(--width);
            background-color: #1b8600;

            position: absolute;
            top: 0; left: 0;
            z-index: -1;
        }

    </style>

@endsection