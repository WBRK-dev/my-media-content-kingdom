@extends("channel.root")

@section("channel-content")
    
    <div>
        <p class="fs-5">Edit Username</p>
        <form method="POST">
            @method("PUT")
            @csrf
            <input type="hidden" name="type" value="name">
            <input type="text" name="name" value="{{ $channel->name }}" autocomplete="off" class="channel-settings-input-text">
            <button class="channel-settings-button">Update</button>
        </form>
    </div>

    <div>
        <p class="fs-5">Edit Profile Picture</p>
        <form method="POST" enctype="multipart/form-data">
            @method("PUT")
            @csrf
            <input type="hidden" name="type" value="profile">
            <input type="file" name="image" accept="image/*" class="channel-settings-input-file">
            <button class="channel-settings-button">Update</button>
        </form>
    </div>

    <div>
        <p class="fs-5">Edit Banner</p>
        <form method="POST" enctype="multipart/form-data">
            @method("PUT")
            @csrf
            <input type="hidden" name="type" value="banner">
            <input type="file" name="image" accept="image/*" class="channel-settings-input-file">
            <button class="channel-settings-button">Update</button>
        </form>
    </div>

@endsection