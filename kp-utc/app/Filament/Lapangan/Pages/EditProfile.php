<?php

namespace App\Filament\Lapangan\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class EditProfile extends Page
{
    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Edit Profile';
    protected static ?string $navigationGroup = null;
    protected static string $view = 'filament.lapangan.pages.edit-profile';

    public $username;
    public $name;
    public $email;
    public $password;

    public function mount()
    {
        $user = Auth::user();
        $this->username = $user->username;
        $this->name = $user->name;
        $this->email = $user->email;
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('username')
                ->label('Username')
                ->required()
                ->maxLength(50),
            Forms\Components\TextInput::make('name')
                ->label('Name')
                ->required(),
            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->email()
                ->required(),
            Forms\Components\TextInput::make('password')
                ->label('New Password')
                ->password()
                ->dehydrateStateUsing(fn ($state) => $state ? Hash::make($state) : null)
                ->dehydrated(fn ($state) => filled($state)),
        ];
    }

    public function submit()
    {
        $user = Auth::user();
        $user->username = $this->username;
        $user->name = $this->name;
        $user->email = $this->email;
        if ($this->password) {
            $user->password = Hash::make($this->password);
        }
        $user->save();
        session()->flash('success', 'Profile updated successfully.');
    }
}
