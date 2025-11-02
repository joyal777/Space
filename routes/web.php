<?php

use App\Http\Controllers\front\ProjectController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Front\DashboardController;
use App\Http\Controllers\front\TaskController;
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::resource('tasks', TaskController::class);


Route::resource('projects', ProjectController::class);
Route::post('/projects/{project}/invite', [ProjectController::class, 'inviteUser'])->name('projects.invite');
Route::post('/projects/{project}/accept-invitation', [ProjectController::class, 'acceptInvitation'])->name('projects.accept-invitation');
Route::post('/projects/{project}/reject-invitation', [ProjectController::class, 'rejectInvitation'])->name('projects.reject-invitation');
Route::delete('/projects/{project}/users/{user}', [ProjectController::class, 'removeUser'])->name('projects.users.remove');
Route::get('test-inertia', function () {
    return Inertia::render('Test');
});


Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
