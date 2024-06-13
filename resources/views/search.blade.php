@extends('layout.root')

@section('head')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let queryElem = document.getElementById("search-bar");
        let filterElem = document.getElementById("filter-query");
        filterElem.value = queryElem.value;

        queryElem.addEventListener("keydown", function(e) {
            filterElem.value = e.target.value;
        });

        queryElem.addEventListener("keyup", function(e) {
            filterElem.value = e.target.value;
        });

        let filterSelected = ;
        let sortSelected = ;
        console.log(filterSelected, sortSelected);
    });
</script>
@endsection

@section('body')
    <main class="d-flex flex-column gap-2 p-2">

        <div class="p-2" style="background-color: var(--body-secondary-bg); border-radius: 1rem;">
            <form>
                <p class="fs-5 fw-bold mb-2">Search Filters</p>
    
                <div class="d-flex gap-2">
    
                    <div>
                        <p class="fw-bold mb-1">Filter on</p>
                        <input type="radio" name="filter" id="created_at" value="created_at"{{ $_GET["filter"] === "created_at" ? "checked" : "" }}><label for="created_at">Release Date</label><br>
                        <input type="radio" name="filter" id="length" value="length"{{ $_GET["filter"] === "length" ? "checked" : "" }}><label for="length">Length</label><br>
                        <input type="radio" name="filter" id="views" value="views"{{ $_GET["filter"] === "views" ? "checked" : "" }}><label for="views">Views</label><br>
                        <input type="radio" name="filter" id="likes" value="likes"{{ $_GET["filter"] === "likes" ? "checked" : "" }}><label for="likes">Likes</label>
                    </div>
                    
                    <div>
                        <p class="fw-bold mb-1">Sorting</p>
                        <input type="radio" name="sort" id="desc" value="desc"{{ $_GET["sort"] === "desc" ? "checked" : "checked" }}><label for="desc">Desc</label><br>
                        <input type="radio" name="sort" id="asc" value="asc"{{ $_GET["sort"] === "asc" ? "checked" : "" }}><label for="asc">Asc</label>
                    </div>
                    
                    <input type="hidden" name="q" id="filter-query" value="">

                </div>

                <div class="d-flex mt-2"><button class="">Filter</button></div>
            </form>

        </div>

        <video-grid>
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