@extends("app")

@section("content")

    <div class="container" style="text-align: center;">
        <button class="btn btn-outline-success" onclick="register(event)">register</button>
    </div>

    <div class="content">
        <div class="title m-b-md animate__animated animate__tada">
            Welcome to the Game
        </div>
        
        <a type="button" href="/api/setupScreen" class="btn btn-outline-info" onclick="login()">Go to setup page</a>

    </div>

    <div class="container" id="registered">

    </div>

@endsection
        
