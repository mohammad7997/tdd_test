<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>

    @auth
        @if (auth()->user()->type == 'admin')
            <a> Test for admin type </a>
        @endif
    @endauth

    @yield('content')
</body>
</html>
