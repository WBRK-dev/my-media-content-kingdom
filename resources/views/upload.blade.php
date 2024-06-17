@extends("layout.root")

@section("body")

    <div class="p-2">

        <h1>Upload</h1>
        <form action="{{ config("app.url") }}/upload" method="post" enctype="multipart/form-data">
    
            @csrf
    
            <label for="title">Title: </label>
            <input type="text" name="title" id="title" required style="width: 500px;" autocomplete="off">
    
            <br><br>
    
            <label for="image">Select a thumbnail: </label>
            <input type="file" name="image" accept="image/*">
    
            <br><br>
    
            <label for="image">Select a video: </label>
            <input type="file" name="video" required accept=".mp4">
    
            <br><br>
    
            <p>Video visibility</p>
            <input type="radio" name="visibility" value="public" id="visibilitypublic1" checked><label for="visibilitypublic1">Public</label><br>
            <input type="radio" name="visibility" value="private" id="visibilityprivate1"><label for="visibilityprivate1">Private</label>
    
            <br><br>
    
            <button>Upload</button>
    
        </form>

        <h1 class="mt-2">Upload from Youtube</h1>
        <form action="{{ config("app.url") }}/upload-youtube" method="post">
    
            @csrf
    
            <label for="youtubeid">Youtube ID: </label>
            <input type="text" name="youtubeid" id="youtubeid" required style="width: 500px;" autocomplete="off">
    
            <br><br>
    
            <p>Video visibility</p>
            <input type="radio" name="visibility" value="public" id="visibilitypublic2" checked><label for="visibilitypublic2">Public</label><br>
            <input type="radio" name="visibility" value="private" id="visibilityprivate2"><label for="visibilityprivate2">Private</label>
    
            <br><br>
    
            <button>Upload</button>

            
        </form>
        
        @if($errors->any())
            <div class="alert alert-danger mt-4">
                <ul> 
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>
    

@endsection