<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8" />
        <meta name="application-name" content="{{ config('app.name') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>{{ config('app.name') }}</title>

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        @filamentStyles
        @vite('resources/css/app.css')
        @livewireStyles
    </head>

    <body class="h-full antialiased bg-gray-50" x-data="{ open: false }">
        <x-navigation />

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            {{ $slot }}
        </main>

        @filamentScripts
        @vite('resources/js/app.js')
        @livewireScripts
    </body>
</html>
