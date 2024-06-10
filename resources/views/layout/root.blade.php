<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ config("app.url") }}/default-css/index.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    @vite(['resources/js/app.js'])
    @yield('head')
    <title>{{ config("app.name") }}</title>
</head>
<body style="height: 100vh; height: 100dvh;">
    @include("layout.navbar")
    <div class="main-layout">
        @include('layout.sidebar')
        <div class="main-layout-body">
            @yield('body')
        </div>
    </div>
</body>
</html>