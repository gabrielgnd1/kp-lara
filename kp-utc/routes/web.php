<?php

use App\Livewire\Auth\Login as LoginComponent;
use App\Livewire\Auth\Register as RegisterComponent;
use App\Livewire\Home;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\ReservasiViewController;

// Public routes
Route::get('/', fn () => redirect('/login')); // redirect root ke login
Route::get('/login', LoginComponent::class)->name('login');
Route::get('/register', RegisterComponent::class)->name('register');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/reservasi/pdf', [ReservasiController::class, 'printPdf']);
    Route::get('/reservasi/{id}', [ReservasiViewController::class, 'show'])->name('reservasi.detail');
    Route::get('/home', Home::class)->name('home');
});

Route::post('/logout', function () {
    auth()->logout();
    return redirect()->route('login');
})->name('logout');

Route::get('/home', Home::class)->middleware('auth')->name('home');

