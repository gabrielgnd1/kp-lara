<?php

return [

    // … isi yang sudah ada di atas tetap biarin …

    /*
    |--------------------------------------------------------------------------
    | Color Palette
    |--------------------------------------------------------------------------
    |
    | Warna utama yang akan dipakai Filament untuk tombol, link, sidebar aktif,
    | dsb. Kamu bisa atur sesuai brand kamu.
    |
    */

    'colors' => [
        'primary' => '#A8DE30',   // Main green
        'secondary' => '#493852', // Purple
        'success' => '#A8DE30',   // Main green
        'warning' => '#D4AF37',   // Gold (kept for warnings)
        'danger'  => '#dc2626',   // Red (kept for errors)
        'info'    => '#493852',   // Purple for info
        'gray' => '#31312C',      // Black for gray shades
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Locale
    |--------------------------------------------------------------------------
    |
    | The default locale that will be used by Filament.
    |
    */
    'default_locale' => 'id',
    'fallback_locale' => 'id',

    /*
    |--------------------------------------------------------------------------
    | Broadcasting
    |--------------------------------------------------------------------------
    */
    'broadcasting' => [
        // …
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    */
    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Assets Path
    |--------------------------------------------------------------------------
    */
    'assets_path' => null,

    /*
    |--------------------------------------------------------------------------
    | Cache Path
    |--------------------------------------------------------------------------
    */
    'cache_path' => base_path('bootstrap/cache/filament'),

    /*
    |--------------------------------------------------------------------------
    | Livewire Loading Delay
    |--------------------------------------------------------------------------
    */
    'livewire_loading_delay' => 'default',

    /*
    |--------------------------------------------------------------------------
    | System Route Prefix
    |--------------------------------------------------------------------------
    */
    'system_route_prefix' => 'filament',
];
