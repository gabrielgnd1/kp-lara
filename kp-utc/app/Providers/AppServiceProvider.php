<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(FilamentLanguageServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Log semua query yang menyentuh tabel pemesanan_fasilitas
        DB::listen(function ($query) {
            if (Str::contains($query->sql, 'pemesanan_fasilitas')) {
                \Log::debug('PF SQL', [
                    'sql'      => $query->sql,
                    'bindings' => $query->bindings,
                    'time_ms'  => $query->time,
                    // jejak pemanggil (biar tahu file/fungsi mana yang nembak query)
                    'trace'    => collect(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 12))
                                    ->map(fn ($f) => ($f['class'] ?? '') . '::' . ($f['function'] ?? ''))
                                    ->implode(' <- '),
                ]);
            }
        });
    }
}
