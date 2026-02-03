<?php

use App\Livewire\Auth\Login as LoginComponent;
use App\Livewire\Auth\Register as RegisterComponent;
use App\Livewire\Home;
use App\Livewire\LaporanList;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\ReservasiViewController;
use App\Http\Controllers\DiskusiLaporanController;
use App\Http\Controllers\DiscussionViewController;
use App\Http\Controllers\Admin\LaporanPrintController;

// Public routes
Route::get('/', fn () => redirect('/login')); // redirect root ke login
Route::get('/login', LoginComponent::class)->name('login');
Route::get('/register', RegisterComponent::class)->name('register');
Route::get('/user-manual', fn () => view('user-manual'))->name('user-manual');

// Protected routes (require authentication)
Route::middleware('auth')->group(function () {
    Route::get('/reservasi/pdf', [ReservasiController::class, 'printPdf']);
    Route::get('/reservasi/{id}', [ReservasiViewController::class, 'show'])->name('reservasi.detail');
    Route::get('/home', Home::class)->name('home');
    
    // Laporan and Discussion Routes
    Route::get('/laporan', LaporanList::class)->name('laporan.list');
    Route::get('/laporan/{laporanId}/discussion', [DiscussionViewController::class, 'show'])->name('discussion.show');
    Route::post('/discussion/store', [DiscussionViewController::class, 'store'])->name('diskusi.store');
    
    // Admin Laporan Print
    Route::get('/admin/laporan/{id}/print', [LaporanPrintController::class, 'print'])->name('admin.laporan.print');
    
    // API routes for discussions
    Route::get('/api/diskusi/{laporanId}', [DiskusiLaporanController::class, 'getDiscussions'])->name('diskusi.get');
    Route::delete('/api/diskusi/{diskusiId}', [DiskusiLaporanController::class, 'destroy'])->name('diskusi.delete');
});

Route::post('/logout', function () {
    auth()->logout();
    return redirect()->route('login');
})->name('logout');

Route::get('/home', Home::class)->middleware('auth')->name('home');

