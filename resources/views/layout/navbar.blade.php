<div class="topnavbar">

    <a href="{{ config("app.url") }}" class="logo">MMCK</a>

    <form method="GET" action="{{ config('app.url') }}/search">
        <div class="d-flex w-100">
            <input type="hidden" name="filter" value="created_at">
            <input type="hidden" name="sort" value="desc">
            <input type="text" id="search-bar" class="search-bar" name="q" value="{{ isset($_GET['q']) ? $_GET['q'] : '' }}" placeholder="Search..." autocomplete="off">
            <button type="button"class="delete" onclick="clearSearchBar()"><span class="material-symbols-outlined">close </span></button>
        </div>
        <div class="d-flex justify-content-center p-2 gap-4" id="filter-buttons">
        </div>
    </form>
    

    @if (Auth::check())
        <div class="d-flex gap-2 align-items-center pe-2">
            <button onclick="toggleUploadPopup()" class="icon-button"><i class="fi fi-sr-plus"></i></button>
            
            <?php $accountDropdownUser = auth()->user(); ?>
            <div class="account-dropdown-wrapper">
                <button onclick="toggleAccountPopup()"><img src="{{config("app.url")}}/api/channel/picture?id={{$accountDropdownUser->id}}&type=profile"></button>

                <div class="account-dropdown">
                    <div class="img-wrapper">
                        <img src="{{config("app.url")}}/api/channel/picture?id={{$accountDropdownUser->id}}&type=banner" class="banner">
                        <img src="{{config("app.url")}}/api/channel/picture?id={{$accountDropdownUser->id}}&type=profile" class="profile">
                    </div>
                    <p class="channel-name">{{$accountDropdownUser->name}}</p>
                    <div class="item-list">
                        <a href="{{config("app.url")}}/channel/{{$accountDropdownUser->id}}" class="list-item"> <i class="fi fi-sr-user"></i> Channel</a>
                        <a href="{{config("app.url")}}/channel/{{$accountDropdownUser->id}}?page=videos" class="list-item"> <i class="fi fi-sr-film"></i> Your Videos</a>
                        <a href="{{config("app.url")}}/upload-queue" class="list-item"> <i class="fi fi-sr-cloud"></i> Upload Queue</a>
                    </div>
                    <div class="item-list" style="margin-top: -1rem;">
                        <a href="{{config("app.url")}}/channel/{{$accountDropdownUser->id}}?page=settings" class="list-item"> <i class="fi fi-sr-settings"></i> Settings</a>
                        <form action="{{config("app.url")}}/logout" method="post">@csrf <button class="list-item w-100"> <i class="fi fi-sr-exit"></i> Sign out</button> </form>
                    </div>
                </div>

            </div>

        </div>
    @else
        <a href="{{ config("app.url") }}/login" class="login">Login</a>
    @endif
</div>