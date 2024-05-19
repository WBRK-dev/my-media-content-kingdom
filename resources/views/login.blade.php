@extends("layout.root")

@section("body")
    
    <form action="{{ config("app.url") }}/login" method="post">
        @csrf

        <input type="text" name="email" placeholder="Email"><br>
        <input type="password" name="password" placeholder="Password"><br>
        <button>Login</button>

    </form>

@endsection