<?php

namespace App\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Livewire\Component;

class Register extends Component
{
    public $username = '';
    public $name = '';
    public $email = '';
    public $password = '';
    public $passwordConfirmation = '';

    public function register()
    {
        $this->validate([
            'username' => ['required', 'unique:user'],
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:user'],
            'password' => ['required', 'min:8', 'same:passwordConfirmation'],
        ]);

        $user = User::create([
            'username' => $this->username,
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => Hash::make($this->password),
            'id_role'  => 2,
            'status'   => 'Available',
        ]);

        event(new Registered($user));

        //Auth::login($user, true);

        return redirect()->intended(route('home'));
    }

    public function render()
    {
        return view('livewire.auth.register')->extends('layouts.auth');
    }
}
