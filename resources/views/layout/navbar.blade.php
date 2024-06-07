<div class="topnavbar">

    <a href="{{ config("app.url") }}" class="logo">MMCK</a>

    <form method="GET" action="{{ config('app.url') }}/search">
        <div class="d-flex">
            <input type="text" id="search-bar" class="search-bar" name="q" value="{{ isset($_GET['q']) ? $_GET['q'] : '' }}" placeholder="Search..." autocomplete="off">
            <button type="button"class="delete" onclick="clearSearchBar()"><span class="material-symbols-outlined">close </span></button>
        </div>
        <div class="d-flex justify-content-center p-2 gap-4">
            <button name="filter" class="filter-button" value="release_date">Release Date</button>
            <button name="filter" class="filter-button" value="length">Length</button>
            <button name="filter" class="filter-button" value="views">Views</button>
            <button name="filter" class="filter-button" value="likes">Likes</button>
        </div>
    </form>
    

    @if (Auth::check())
        <div class="d-flex gap-2">
            <a href="{{ config("app.url") }}/upload" class="upload">Upload</a>
            <form action="{{ config("app.url") }}/logout" class="logout" method="post">@csrf<button>Logout</button></form>
        </div>
    @else
        <a href="{{ config("app.url") }}/login" class="login">Login</a>
    @endif
</div>

<script>
function clearSearchBar() {
    let elem = document.getElementById("search-bar");
    elem.value = "";
    console.log("test");
}

document.addEventListener('DOMContentLoaded', function() {
    const searchBar = document.querySelector('.search-bar');
    const searchBarInput = document.querySelector('.search-bar-input');

    searchBarInput.addEventListener('focus', function() {
        searchBar.classList.add('active');
    });

    searchBarInput.addEventListener('blur', function() {
        searchBar.classList.remove('active');
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const deleteElement = document.querySelector('.delete');
    const deleteInput = document.querySelector('.delete-input');

    deleteInput.addEventListener('focus', function() {
       deleteElement.classList.add('active');
    });

    deleteInput.addEventListener('blur', function() {
        deleteElement.classList.remove('active');
    });
});
</script>