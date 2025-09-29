<?php

use App\Livewire\Auth\Login as LoginComponent;
use App\Livewire\Auth\Register as RegisterComponent;
use App\Livewire\Home;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservasiController;

Route::get('/reservasi/pdf', [ReservasiController::class, 'printPdf']);


Route::get('/', fn () => redirect('/login')); // redirect root ke login
Route::get('/login', LoginComponent::class)->name('login');
Route::get('/register', RegisterComponent::class)->name('register');

Route::get('/home', Home::class)->middleware('auth')->name('home');


