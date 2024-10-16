<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Личный кабинет</title>
    <!-- Подключение Bootstrap CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    @yield('css')
</head>
<body>
@yield('content')
@yield('js')
<!-- Подключение Bootstrap JS и необходимых библиотек -->
<script src="/js/jquery-3.6.0.min.js"></script>
<script src="/js/popper.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
</body>
</html>

