@extends('layout.root')

@section('body')

    <div class="d-flex justify-content-center">
        <form action="{{ config("app.url") }}/register" method="post" class="d-flex flex-column" style="width: min(300px, 100%);">
            
            @csrf
            <div class="register">
            <input type="text" name="name" placeholder="Name" autocomplete="off">
            <input type="text" name="email" placeholder="Email" autocomplete="off">
            <input type="text" name="password" placeholder="Password" autocomplete="off">
            <input type="text" name="password_confirmation" placeholder="Repeat Password" autocomplete="off">
            <button>Register</button>
            <div class="d-flex pb-1"><p>Already have an account?</p><a href="{{ config("app.url") }}/login" class="ps-1 pe-1" style="color: #00b4d8; text-decoration: none;">Log in</a><p>here.</p></div>
        </div>
       

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