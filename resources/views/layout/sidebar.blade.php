<div class="sidebar">
    <div class="sidebar-item home-item">
        <span class="material-symbols-outlined">home</span>
        <a href="{{ config('app.url') }}">Home</a>
    </div>
    @auth
        <div class="sidebar-item">
            <span class="material-symbols-outlined">person</span>
            <a href="{{ config('app.url') }}/channel/{{ Auth::user()->id }}">Your Channel</a>
        </div>
        <div class="sidebar-item last-item">
            <span class="material-symbols-outlined">playlist_play</span>
            <a href="{{ config('app.url') }}">Playlists</a>
        </div>
    @endauth
    
</div>