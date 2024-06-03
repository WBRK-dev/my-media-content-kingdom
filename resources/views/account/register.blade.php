@extends('layout.root')

@section('body')

    <div class="d-flex justify-content-center">
        <form action="{{ config("app.url") }}/register" method="post" class="d-flex flex-column" style="width: min(300px, 100%);">
            
            @csrf
    
            <input type="text" name="name" placeholder="Name">
            <input type="text" name="email" placeholder="Email">
            <input type="text" name="password" placeholder="Password">
            <input type="text" name="password_confirmation" placeholder="Password">
            <button>Register</button>

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