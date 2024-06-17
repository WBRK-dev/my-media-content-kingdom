@extends("layout.root")

@section("body")
<div class="login-form">
    <form action="{{ config("app.url") }}/login" method="post">
        @csrf
        <input type="text" name="email" placeholder="Email" autocomplete="off"><br>
        <input type="password" name="password" placeholder="Password" autocomplete="off"><br>
        <button>Login</button>
    </form>
</div>


@endsection