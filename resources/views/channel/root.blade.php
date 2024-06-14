@extends("layout.root")

@section("body")
    
    <div class="d-flex flex-column gap-2 p-2 pt-0">

        <div class="channel-head">
            <img src="{{config("app.url")}}/api/channel/picture?id={{$channel->id}}&type=banner" class="head-img">
            <div class="channel-info">
                <img src="{{config("app.url")}}/api/channel/picture?id={{$channel->id}}&type=profile" class="channel-pp">
                <div>
                    <div class="channel-name">{{ $channel->name }}</div>
                    <div class="channel-desc">Videos: {{ $channel->videos->count() }}</div>
                </div>
            </div>
        </div>

        <div class="section-rail">
            <a href="{{config("app.url")}}/channel/{{$channel->id}}" class="rail-button {{ $page === "home" ? "active": "" }}">Home</a>
            <a href="{{config("app.url")}}/channel/{{$channel->id}}?page=videos" class="rail-button {{ $page === "videos" ? "active": "" }}">Videos</a>

            @if ($isChannelOwner)
                <a href="{{config("app.url")}}/channel/{{$channel->id}}?page=settings" class="rail-button {{ $page === "settings" ? "active": "" }}">Settings</a>
            @endif

        </div>

        @yield("channel-content")

    </div>

@endsection

@section("head")
    
    <link rel="stylesheet" href="{{config("app.url")}}/paged-css/channel/index.css">
    @yield("channel-head")

@endsection