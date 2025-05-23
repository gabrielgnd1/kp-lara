<?php

use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\LogoutController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Passwords\Confirm;
use App\Livewire\Auth\Passwords\Email;
use App\Livewire\Auth\Passwords\Reset;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\Verify;
use Illuminate\Support\Facades\Route;

//file web.php ini itu buat kita deklarasi routing
//contoh: Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
//ini artinya jika url adalah ../users/create, maka dia akan menjalankan fungsi create di UserController.php
//-> name itu buat ngasi nama ke route itu, jadi kayak dikasi nickname gitu

//group() artinya membuat sekelompok function yang hanya bisa dijalankan oleh user tertentu
//auth -> memastikan user sudah login, jika belum akan otomatis diarahkan ke halaman login
//isSuperAdmin ini ada di app/Providers/AuthServiceProvider.php
//intinya route-route ini hanya bisa dijalankan jika role user adalah super admin   
Route::middleware(['auth', 'can:isSuperAdmin'])->group(function () {
    //.get ini buat lihat halaman, tampilin form, ambil data (SELECT)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    //.post ini buat CREATE atau INSERT ke database dari form
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    //.put buat EDIT ke database dari form
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
}); 