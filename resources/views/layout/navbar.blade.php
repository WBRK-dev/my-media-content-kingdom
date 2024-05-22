<div class="topnavbar">

    <a href="{{ config("app.url") }}" class="logo">MMCK</a>

    <form method="GET" action="{{ config('app.url') }}/search">
        <input type="text" name="q" placeholder="Search..." autocomplete="off">
    </form>
</div>