<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Facades\Filament;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class Login extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();

        if (Auth::check() && Auth::user()->id_role !== 1) {
            abort(403, 'Unauthorized. Admin access required.');
        }
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('username')
                ->label('Username')
                ->required()
                ->autocomplete()
                ->autofocus(),
            $this->getPasswordFormComponent(),
            $this->getRememberFormComponent(),
        ]);
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