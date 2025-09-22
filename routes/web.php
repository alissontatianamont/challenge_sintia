<?php
use Illuminate\Support\Facades\Route;
use App\Livewire\FilesModule\UploadFile;

Route::redirect('/', '/login');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/upload-file', UploadFile::class)
    ->middleware(['auth'])
    ->name('file.upload');


require __DIR__.'/auth.php';
