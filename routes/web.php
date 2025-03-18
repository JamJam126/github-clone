<?php

use App\Models\Repo;
use App\Models\User;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepoController;
use App\Http\Controllers\CommitController;
use App\Http\Controllers\FileController;
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

Route::get('/home', function () {
    return Inertia::render('Home');
})->middleware(['auth', 'verified'])->name('home');

// Route::middleware(['auth', 'verified'])->group(function () {
//     // Route::get('/new', Inertia::render('New'))->name('new');
//     Route::get('/home', Inertia::render('Home'))->name('home');
// });


Route::middleware(['auth', 'verified'])->group(function () {

    // Route::get('home', function () {
    //     return Inertia::render('Home');
    // })->name('home');
    // Route::get('home', function() {
    //     return Inertia::render('Home');
    // })->name('home');
    Route::get('/', function () {
        return Inertia::render('Home', [
            'repos' =>
            Repo::select('name', 'id')
                ->addSelect([
                    'user_name' => User::select('name')
                        ->whereColumn('id', 'repos.user_id')
                ])
                ->limit(5)
                ->get()
        ]);
    })->name('home');
    // Route::get('/{repo}/', function ($repo) {
    //     return Inertia::render('Repo', [
    //         'info' => Repo::select('name', 'id', 'created_at')
    //                     ->where('name', $repo)
    //                     ->first()
    //     ]);
    // })->name('repo.show');
    // Route::get('/{repo}/', [RepoController::class, 'repo'])->name('repo.show');
    //Route::get('/{repo}/tree/{folder}', [RepoController::class, 'subdir'])->name('repo.subdir');
    //Route::get('/{repo}/tree/{folder}/{file}', [RepoController::class, 'displayFileContent'])->name('subdir.filecontent');
    Route::get('/fileTree/{repo}/{folder}/{folder_id}', [RepoController::class, 'getChildren'])->name('folder.children'); // Testing
    Route::get('/{user}/{repo}', [RepoController::class, 'repo'])->name('repo.show');
    Route::get('/{user}/{repo}/{file}', [RepoController::class, 'displayRootFileContent'])->name('repo.filecontent');
    Route::get('/{user}/{repo}/tree/{path}',[RepoController::class, "folderHandler"])->where('path', '.*')->name("repo.folderhandle");
    Route::get("/{user}/{repo}/blob/{path}", [RepoController::class, "fileHandler"])->where('path', '.*')->name("repo.filehandler");
    Route::get('/new', [RepoController::class, 'createRepo'])->name('new');
    Route::get('/home', [RepoController::class, 'getRepo'])->name('repos');
    Route::post('/store/repository', [RepoController::class, 'store'])->name('repos.store');
    Route::post('/{user}/{repo}', [CommitController::class, 'store'])->name('files.commit');
    Route::post('/commited-files', [FileController::class, 'store'])->name('commited.files');

    // {star} is boolean of user stars or unstars the repo 
    Route::get('/star/{star}/{user}/{repo}', [RepoController::class, 'handleStar']);

    Route::get('/pin/{pin}/{user}/{repo}', [RepoController::class, 'handlePin']);
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
