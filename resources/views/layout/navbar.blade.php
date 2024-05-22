<div class="topnavbar">

    <a href="{{ config("app.url") }}" class="logo">MMCK</a>

    <form method="GET" action="{{ config('app.url') }}/search">
        <input type="text" name="q" placeholder="Search..." autocomplete="off">
    </form>

    @auth
        <a href="{{ config("app.url") }}/upload">Upload</a>
    @endauth
    <a href="{{ config("app.url") }}/login">Login</a>
</div>