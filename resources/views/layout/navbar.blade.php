<div class="topnavbar">

    <a href="{{ config("app.url") }}" class="logo">MMCK</a>

    <form method="GET" action="{{ config('app.url') }}/search">
        <input type="text" name="q" placeholder="Search..." autocomplete="off">
    </form>

    @if (Auth::check())
        <a href="{{ config("app.url") }}/upload">Upload</a>
    @else
        <a href="{{ config("app.url") }}/login">Login</a>
    @endif
</div>