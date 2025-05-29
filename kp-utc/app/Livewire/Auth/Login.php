<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

class Login extends Component
{
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
        ], $this->remember)) {
            session()->regenerate();

            $user = Auth::user();

            //id 1 = "supervisor"
            if ($user->id_role == 3 && $user->status === 'Available') {
                return redirect(Filament::getPanel('lapangan')->getUrl());
            }

            //id 2 = "admin"
            if ($user->id_role == 1 && $user->status === 'Available') {
                return redirect(Filament::getPanel('admin')->getUrl());
            }

            //id 3 = "superadmin"

            //tinggal tambah panel lain 

            Auth::logout();
            $this->addError('email', 'Akun Anda tidak memiliki akses yang valid.');
        } else {
            $this->addError('email', 'Email atau password salah.');
        }
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.filament');
    }
}
