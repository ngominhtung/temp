<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@lang('register.title')</title>

        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <link rel="stylesheet" href="{{ mix('/css/admin.css') }}">
        <link rel="stylesheet" href="{{asset('/css/fontawesome-all.css')}}">
    </head>
    <body>
        @yield('main')
    </body>
    <script src="{{ mix('js/app.js') }}"></script>
        @yield('js')
</html>
