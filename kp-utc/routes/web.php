<?php


use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;

Route::get('/login', function () {
    return \Livewire\Livewire::mount('auth.login')->html();
})->name('login');
