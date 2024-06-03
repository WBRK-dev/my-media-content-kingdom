<div class="topnavbar">

    <a href="{{ config("app.url") }}" class="logo">MMCK</a>

    <form method="GET" action="{{ config('app.url') }}/search">
        <input type="text" name="q" value="{{ isset($_GET['q']) ? $_GET['q'] : '' }}" placeholder="Search..." autocomplete="off">
        <button name="filter" value="release_date">Release Date</button>
        <button name="filter" value="length">Length</button>
        <button name="filter" value="views">Views</button>
        <button name="filter" value="likes">Likes</button>
    </form>

    @if (Auth::check())
        <div class="d-flex gap-2">
            <a href="{{ config("app.url") }}/upload">Upload</a>
            <form action="{{ config("app.url") }}/logout" method="post">@csrf<button>Logout</button></form>
        </div>
    @else
        <a href="{{ config("app.url") }}/login">Login</a>
    @endif
</div>