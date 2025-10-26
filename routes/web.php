<?php

use App\Http\Controllers\front\ProjectController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\front\TaskController;
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::resource('tasks', TaskController::class);


Route::resource('projects', ProjectController::class);
Route::get('test-inertia', function () {
    return Inertia::render('Test');
});
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
