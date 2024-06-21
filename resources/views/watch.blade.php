@extends('layout.root')

@section('title', $video->title.' - ')

@section('body')
    
    <div class="d-flex gap-2">

        <div style="display: flex; justify-content: center; flex-grow: 1;"><div style="width: min(100%, 1000px);">
    
            <video controls muted style="display: block; width: 100%; aspect-ratio: 16/9; background-color: #474747;"></video>
    
            <div class="video-title">{{$video->title}}</div>

            <div class="video-details">
                <div class="channel py-2 d-flex">
                    <a href="{{config("app.url")}}/channel/{{$video->owner->id}}"><img src="{{config("app.url")}}/api/channel/picture?id={{$video->owner->id}}&type=profile"></a>
                    <div class="info">
                        <a href="{{config("app.url")}}/channel/{{$video->owner->id}}">{{$video->owner->name}}</a>
                        @if ($video->owner->videos->count() == 1)
                            <div class="videos">{{$video->owner->videos->count()}} video</div>
                        @else
                            <div class="videos">{{$video->owner->videos->count()}} videos</div>
                        @endif
                    </div>
                </div>

                <div>
                    @auth
                    <div class="d-flex gap-2 buttons">
                        <div class="d-flex gap-4 likebuttons">
                            <div class="d-flex gap-1 likeelement">
                                <input type="checkbox" id="checkboxlike" {{ $likestatus === 1 ? 'checked' : '' }}>
                                <label for="checkboxlike" class="icon likelabel"><i class="fi fi-sr-thumbs-up"></i></label>
                                <div id="likeAmount">{{$video->getLikes()}}</div>
                            </div>
                            <div class="d-flex gap-1 dislikeelement">
                                <input type="checkbox" id="checkboxdislike" {{ $likestatus === 0 ? 'checked' : '' }}>
                                <label for="checkboxdislike" class="icon dislikelabel"><i class="fi fi-sr-thumbs-down"></i></label>
                                <div id="dislikeAmount">{{$video->getDislikes()}}</div>
                            </div>
                        </div>
                        <div>
                            <button id="openformbutton" class="button"><i class="fi fi-sr-flag-alt"></i></button>
                        </div>
                        <div>
                            <select class="resolutionchange" onchange="updateResolution(this)"></select>
                        </div>
                    </div>
                    
                    <div id="report-card" class="report-card px-4 py-2 text-white bg-secondary">
                        <div class="card-header">Report video</div>
                        <div class="card-content d-flex flex-column my-3">
                            <form>
                                @csrf
                                @foreach (config('app.report_reasons') as $reason)
                                    <div class="my-2">
                                        <input type="radio" id="reason{{$loop->index}}" name="report_reason" value="{{$loop->index}}">
                                        <label for="reason{{$loop->index}}">{{$reason}}</label>
                                    </div>
                                @endforeach
                            </form>
                        </div>
                        <div class="card-footer d-flex">
                            <button id="formBtn" type="button">Submit</button>
                            <button id="closeformbutton">X</button>
                        </div>
                    </div>
                    @else
                    <div class="d-flex gap-2 buttons">
                        <div>
                            <select class="resolutionchange" onchange="updateResolution(this)"></select>
                        </div>
                    </div>
                    @endauth
                </div>
                
            </div>

            <div class="video-description">
                <div class="description-header d-flex gap-2">
                    <div class="viewcount">
                        @if ($video->getViews() == 1)
                            <div>{{$video->getViews()}} view</div>
                        @else
                            <div>{{$video->getViews()}} views</div>
                        @endif
                    </div>
                    <div class="upload-date">
                        <div>{{$video->created_at->format('M j, Y')}}</div>
                    </div>
                </div>
                <textarea class="video-description-value" disabled>{{$video->description}}</textarea>
            </div>
            
        </div></div>

        <div style="width: 450px; flex-shrink: 0;" class="d-flex flex-column gap-2">
            @foreach ($videos as $item)
                @if ($item->id == $video->id) @continue @endif
                <video-watch-item class="{{ $item->isFromYoutube() ? "youtube" : "" }}">
                    <a href="{{ config('app.url') }}/watch?id={{ $item->getId() }}" class="img-wrapper">
                        <img  src="{{ config('app.url') }}/api/thumbnail?id={{ $item->thumbnail->id }}">
                        <p class="tag">{{ $item->longDuration() }}</p>
                    </a>
                    <div class="content-wrapper {{ $item->isNew() ? 'has-new-tag' : '' }}">
                        <a href="{{ config('app.url') }}/watch?id={{ $item->getId() }}" class="title">{{ $item->title }}</a>
                        <div class="info">
                            <a href="{{ config('app.url') }}/channel/{{ $item->owner->id }}" class="d-flex">{{ $item->owner->name }}</a>
                            <a href="{{ config('app.url') }}/watch?id={{ $item->getId() }}" class="d-flex">
                                @if ($item->getViews() == 1)
                                    {{$item->getViews()}} view
                                @else
                                    {{$item->getViews()}} views
                                @endif

                                &#x2022;
                                
                                {{ $item->getTimeAgo() }}
                            </a>
                        </div>
                        @if ($item->isNew())
                            <a href="{{ config('app.url') }}/watch?id={{ $item->getId() }}" class="new-tag" style="display: inline-block;">NEW</a>                            
                        @endif
                    </div>
                </video-watch-item>  
            @endforeach
        </div>

    </div>



@endsection

@section('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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


            let likeElement = document.getElementById("checkboxlike");
            let dislikeElement = document.getElementById("checkboxdislike");
            
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

            const formBtn = document.getElementById("formBtn");
            formBtn.addEventListener("click", formSubmit);
            
            document.getElementById("openformbutton").addEventListener("click", showReportBox);
            document.getElementById("closeformbutton").addEventListener("click", closeReportBox); 

            const textarea = document.querySelector('.video-description-value');

            function adjustTextareaHeight() {
                textarea.style.height = 'auto';
                textarea.style.height = textarea.scrollHeight + "px";
            }

            adjustTextareaHeight();
        });

        function updateResolution(elem) {
            hls.currentLevel = Number(elem.value);
        }

        function formSubmit() {
            const formData = new FormData();
            console.log(document.querySelector('input[name="report_reason"]:checked').value);
            formData.append(
                'reason_id',
                document.querySelector('input[name="report_reason"]:checked').value
            );
            formData.append(
                'id',
                "{{$video->id}}"
            );
            formData.append(
                '_token',
                "{{csrf_token()}}"
            );

            fetch('{{config("app.url")}}/api/watch/report_video',
            {
                method: "POST",
                body: formData,
            })
            closeReportBox();

        }

        function showReportBox() {
            let checkedBtn = document.querySelector('input[name="report_reason"]:checked');
            if (checkedBtn) checkedBtn.checked = false;
            let reportBox = document.getElementById("report-card");
            if (reportBox.style.display = "none") {
                reportBox.style.display = "block";
            }    
        }

        function closeReportBox() {
            let checkedBtn = document.querySelector('input[name="report_reason"]:checked');
            if (checkedBtn) checkedBtn.checked = false;
            let reportBox = document.getElementById("report-card");
            if (reportBox.style.display = "block") {
                reportBox.style.display = "none";
            }  
        }
        
    </script>
@endsection