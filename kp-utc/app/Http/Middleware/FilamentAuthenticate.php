<?php

namespace App\Http\Middleware;

use Filament\Facades\Filament;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class FilamentAuthenticate extends Middleware
{
    protected function authenticate($request, array $guards): void
    {
        $guard = Filament::auth();

        if (! $guard->check()) {
            $this->unauthenticated($request, $guards);
            return;
        }

        if ($guard->user()->id_role !== 1) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        $this->auth->shouldUse(Filament::getAuthGuard());
    }

    protected function redirectTo($request): string
    {
        return route('filament.auth.login');
    }
}