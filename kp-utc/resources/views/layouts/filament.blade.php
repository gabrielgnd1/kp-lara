<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- atau Livewire asset --}}
    @livewireStyles
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
    {{ $slot }}

    @livewireScripts
</body>
</html>


