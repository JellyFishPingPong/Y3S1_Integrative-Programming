<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My App')</title> <!-- Dynamic page title -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Example of linking CSS -->
    @yield('header')
</head>

<body>
    <div class="pad-header"></div>
    <div class="header">
        <div class="logo">
            
        </div>
        <div class="nav">
            <a class="header-nav">HOME</a>
            <a class="header-nav">GUIDE</a>
            <a href="/forum" class="header-nav">FORUM</a>
            <a class="header-nav">CONSULTATION</a>
            <div></div>
        </div>
        <div class="login">
        </div>
    </div>

    <main>
        @yield('content') <!-- Section where other views will inject content -->
    </main>

    <footer>
        
    </footer>
</body>

</html>