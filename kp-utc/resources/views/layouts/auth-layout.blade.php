<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @filamentStyles
    @vite('resources/css/app.css')
    @livewireStyles
    <style>
        .auth-background {
            background: linear-gradient(135deg, rgb(168 222 48 / 0.1), rgb(73 56 82 / 0.1));
        }
    </style>
</head>
<body class="h-full antialiased auth-background">
    {{ $slot }}

    @filamentScripts
    @vite('resources/js/app.js')
    @livewireScripts
</body>
</html>