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
        <div>
            <button onclick="showReportBox()">Report video</button>
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
                <button onclick="closeReportBox()">X</button>
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

            const formBtn = document.getElementById("formBtn");
            formBtn.addEventListener("click", formSubmit);    
        });

        function updateResolution(elem) {
            hls.currentLevel = Number(elem.value);
        }

        function formSubmit() {
            const formData = new FormData();
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
        }

        function showReportBox() {
            let reportBox = document.getElementById("report-card");
            if (reportBox.style.top = "-50%") {
                reportBox.style.top = "50%";
            }            
        }

        function closeReportBox() {
            let reportBox = document.getElementById("report-card");
            if (reportBox.style.top = "50%") {
                reportBox.style.top = "-50%";
            }  
        }
        
    </script>
@endsection