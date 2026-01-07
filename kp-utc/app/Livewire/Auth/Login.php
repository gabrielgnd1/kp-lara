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

        // First, check if user exists and credentials are correct
        $user = \App\Models\User::where('email', $this->email)->first();
        
        if ($user && \Illuminate\Support\Facades\Hash::check($this->password, $user->password)) {
            // Credentials are correct, now check if account is active
            if ($user->status !== 'Available') {
                $this->addError('email', 'Akun tidak aktif, silahkan hubungi admin.');
                return;
            }
            
            // Account is active, proceed with login
            Auth::login($user, $this->remember);
            session()->regenerate();
            
            // Map role IDs to panel names
            $rolePanels = [
                1 => 'admin',
                2 => 'reservasi',
                3 => 'lapangan',
                4 => 'reservasi', // Allow role 4 to access reservasi panel too
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
