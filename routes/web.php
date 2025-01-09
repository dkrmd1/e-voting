<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\RoleController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Web\CandidateController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\VoterController;

Route::get('/', function () {
    return redirect()->route('app.dashboard');
});

Route::group(['prefix' => 'app', 'as' => 'app.', 'middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('candidate', CandidateController::class);
    Route::resource('voter', VoterController::class);

    Route::resource('role', RoleController::class);
    Route::resource('user', UserController::class);
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
