<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

class Login extends Component
{
    public function layout()
    {
        return 'layouts.auth-layout';
    }
    public $email = '';
    public $password = '';
    public $remember = false;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'email' => $this->email,    
            'password' => $this->password,
            'status' => 'Available',  // Only allow active users
        ], $this->remember)) {
            session()->regenerate();

            $user = Auth::user();
            
            // Map role IDs to panel names
            $rolePanels = [
                1 => 'admin',
                2 => 'reservasi',
                3 => 'lapangan',
            ];

            if (isset($rolePanels[$user->id_role])) {
                $panel = Filament::getPanel($rolePanels[$user->id_role]);
                if ($panel) {
                    return redirect($panel->getUrl());
                }
            }

            Auth::logout();
            $this->addError('email', 'Akun Anda tidak memiliki akses yang valid.');
        } else {
            $this->addError('email', 'Email atau password salah.');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
