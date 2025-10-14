<?php

namespace App\Http\Middleware;

use Filament\Facades\Filament;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class ReservasiPanelAuthenticate extends Middleware
{
    protected function authenticate($request, array $guards): void
    {
        $guard = Filament::auth();

        if (! $guard->check()) {
            $this->unauthenticated($request, $guards);
            return;
        }

        // Allow only users with id_role 2 or 4
        if (!in_array($guard->user()->id_role, [2, 4])) {
            abort(403, 'Unauthorized access.');
        }

        $this->auth->shouldUse(Filament::getAuthGuard());
    }

    protected function redirectTo($request): string
    {
        return route('login');
    }
}