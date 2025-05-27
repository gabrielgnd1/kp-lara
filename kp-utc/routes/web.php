<?php

use App\Livewire\Auth\Login as LoginComponent;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/login')); // redirect root ke login
Route::get('/login', LoginComponent::class)->name('login');