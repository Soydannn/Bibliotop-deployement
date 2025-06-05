<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\FavoriteController;


Route::get('/', [BookController::class, 'index'])
->middleware(['auth'])
->name('home');

Route::get('/books', [BookController::class, 'index']);

Route::get('/book/{id}', [BookController::class, 'show'])->name('show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/favorites', function () {
        return view('favorites.index');
    })->name('favorites.index');
});

Route::middleware('auth')->group(function () {
    Route::post('/favorites/{bookId}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{bookId}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

require __DIR__.'/auth.php';
