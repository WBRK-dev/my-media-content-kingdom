@extends("layout.root")

@section("body")
    
    <h1>Upload</h1>
    <form action="{{ config("app.url") }}/upload" method="post" enctype="multipart/form-data">

        @csrf

        <label for="title">Title: </label>
        <input type="text" name="title" required style="width: 500px;">
        
        <br><br>

        <label for="image">Select a thumbnail: </label>
        <input type="file" name="image" required accept="image/*">

        <br><br>

        <label for="image">Select a video: </label>
        <input type="file" name="video" required accept=".mp4">

        <br><br>

        <button>Upload</button>
        <p style="font-weight: 700; color: red;">
            LETOP: Zorg dat er een user bestaat in de table users met het id 1!<br>
            Het werkt nu even zo omdat er nog geen login pagina bestaat.
        </p>
        

    </form>

@endsection