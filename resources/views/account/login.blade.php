@extends("layout.root")

@section("body")
<div class="login-form">
    <form action="{{ config("app.url") }}/login" method="post">
        @csrf
        <div>
            <input type="text" name="email" placeholder="Email" autocomplete="off"><br>
            <input type="password" name="password" placeholder="Password" autocomplete="off"><br>
            <button style="width: 220px;">Login</button>
        </div>
        <div class="d-flex"><p>No account yet? </p><a href="{{ config("app.url") }}/register" class="ps-1 pe-1" style="color: #00b4d8; text-decoration: none;">Sign up</a><p>here.</p></div>
    </form>
</div>


@endsection