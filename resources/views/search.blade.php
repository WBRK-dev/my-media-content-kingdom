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
                        <input type="radio" name="filter" id="filter1" value="release-date"><label for="filter1">Release Date</label><br>
                        <input type="radio" name="filter" id="filter2" value="length"><label for="filter2">Length</label><br>
                        <input type="radio" name="filter" id="filter3" value="views"><label for="filter3">Views</label><br>
                        <input type="radio" name="filter" id="filter4" value="likes"><label for="filter4">Likes</label>
                    </div>
                    
                    <div>
                        <p class="fw-bold mb-1">Sorting</p>
                        <input type="radio" name="sort" id="sort1" value="desc"><label for="sort1">Descending</label><br>
                        <input type="radio" name="sort" id="sort2" value="asc"><label for="sort2">Ascending</label>
                    </div>
                    
                    <input type="hidden" name="q" id="filter-query" value="">

                </div>

                <div class="d-flex mt-2"><button class="">Filter</button></div>
            </form>

        </div>

        <video-grid>
            @foreach ($videos as $video)
            <div class="video-grid-item">
                <a class="video-grid-item" href="{{ config('app.url') }}/watch?id={{ $video->getId() }}">
                    <img src="{{ config('app.url') }}/api/thumbnail?id={{ $video->thumbnail->id }}" style="width: 100%;">
                    <div class="info">
                        <p class="title">{{ $video->title }}</p>
                        @if ($video->getViews() == 1)
                            <div>{{ $video->getViews() }} view</div>
                        @else
                            <div>{{ $video->getViews() }} views</div>
                        @endif
                    </div>
                </a>
                @permission('video-remove')
                    <form method="post" action="{{ config('app.url') }}/delete/{{ $video->id }}">
                        @csrf
                        @method('DELETE')
                        <button>Delete</button>
                    </form>
                @endpermission
            </div>
            @endforeach
        </video-grid>
    </main>
@endsection