@extends("layout.root")

@section("body")

    <div class="p-2">

        <h1>Upload</h1>
        <form action="{{ config("app.url") }}/upload" method="post" enctype="multipart/form-data">
    
            @csrf
    
            <label for="title">Title: </label>
            <input type="text" name="title" required style="width: 500px;">
            
            <br><br>
    
            <input type="checkbox" name="autogeneratethumbnail" id="autogeneratethumbnail">
            <label for="autogeneratethumbnail">Auto generate thumbnail</label>
    
            <br>
    
            <label for="image">Select a thumbnail: </label>
            <input type="file" name="image" accept="image/*">
    
            <br><br>
    
            <label for="image">Select a video: </label>
            <input type="file" name="video" required accept=".mp4">
    
            <br><br>
    
            <p>Video visibility</p>
            <input type="radio" name="visibility" value="public" id="visibilitypublic" checked><label for="visibilitypublic">Public</label><br>
            <input type="radio" name="visibility" value="private" id="visibilityprivate"><label for="visibilityprivate">Private</label>
    
            <br><br>
    
            <button>Upload</button>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
    
        </form>

    </div>
    

@endsection