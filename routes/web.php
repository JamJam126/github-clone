<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/', function () {
    return Inertia::render('Home');
})->middleware(['auth', 'verified'])->name('home');

// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('/new', Inertia::render('New'))->name('new');
//     Route::get('/home', Inertia::render('Home'))->name('home');
// });

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/', function () {
        return Inertia::render('Home');
    })->name('home');

    Route::get('/home', function () {
        return Inertia::render('Home');
    })->name('home');

    Route::get('/new', [RepoController::class, 'createRepo'])->name('new');
    Route::post('/store/repository', [RepoController::class, 'store'])->name('repositories.store');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
