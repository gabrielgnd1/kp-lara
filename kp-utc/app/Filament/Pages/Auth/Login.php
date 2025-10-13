<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLoginPage;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLoginPage
{
    public function mount(): void
    {
        parent::mount();

        if (auth()->guard('web')->check() && auth()->guard('web')->user()->id_role !== 1) {
            abort(403, 'Unauthorized. Admin access required.');
        }
    }

    public function getHeading(): string|Htmlable
    {
        return 'Admin Login';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Login to access the admin panel';
    }
}