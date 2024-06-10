<div class="topnavbar">

    <a href="{{ config("app.url") }}" class="logo">MMCK</a>

    <form method="GET" action="{{ config('app.url') }}/search">
        <div class="d-flex w-100">
            <input type="text" id="search-bar" class="search-bar" name="q" value="{{ isset($_GET['q']) ? $_GET['q'] : '' }}" placeholder="Search..." autocomplete="off">
            <button type="button"class="delete" onclick="clearSearchBar()"><span class="material-symbols-outlined">close </span></button>
        </div>
        <div class="d-flex justify-content-center p-2 gap-4" id="filter-buttons">
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