<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ReservasiPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->homeUrl(fn () => route('filament.reservasi.pages.dashboard'))
            ->id('reservasi')
            ->path('reservasi')
            ->brandLogo(asset('images/ioc_utc_upc.png'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('images/ioc_utc_upc.png'))
            ->colors([
                'primary' => [
                    50 => '#F5FBF0',
                    100 => '#EBF7E2',
                    200 => '#D5F0B8',
                    300 => '#B7E585',
                    400 => '#A8DE30', // Main green
                    500 => '#97C82B',
                    600 => '#7EA724',
                    700 => '#65861D',
                    800 => '#4C6416',
                    900 => '#32430F',
                    950 => '#192107',
                ],
                'gray' => [
                    50 => '#F9F9F9',
                    100 => '#F3F3F2',
                    200 => '#E6E6E5',
                    300 => '#CDCDCB',
                    400 => '#9A9A98',
                    500 => '#737371',
                    600 => '#565654',
                    700 => '#454543',
                    800 => '#31312C', // Main black
                    900 => '#252521',
                    950 => '#171715',
                ],
                'warning' => Color::Amber, // Keep amber for warnings
                'danger' => Color::Rose,   // Keep rose for errors
                'info' => [                // Purple for info
                    50 => '#F4F2F5',
                    100 => '#E6E2EC',
                    200 => '#D5CDD9',
                    300 => '#B6A8BC',
                    400 => '#867091',
                    500 => '#493852', // Main purple
                    600 => '#42324A',
                    700 => '#372A3E',
                    800 => '#2C2131',
                    900 => '#211925',
                    950 => '#161119',
                ],
            ])
            ->discoverResources(in: app_path('Filament/Reservasi/Resources'), for: 'App\\Filament\\Reservasi\\Resources')
            ->pages([
                \App\Filament\Reservasi\Pages\Dashboard::class,
                \App\Filament\Reservasi\Pages\EditProfile::class,
            ])
            ->discoverPages(in: app_path('Filament/Reservasi/Pages'), for: 'App\\Filament\\Reservasi\\Pages')
            ->discoverWidgets(in: app_path('Filament/Reservasi/Widgets'), for: 'App\\Filament\\Reservasi\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                //Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                \App\Http\Middleware\ReservasiPanelAuthenticate::class,
            ]);
    }
}
