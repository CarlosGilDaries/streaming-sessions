<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSessionController;
use App\Http\Middleware\EnsureDeviceSessionExists;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware(['auth'])->group(function () {
    Route::get('principal', function () {
        return view('principal');
    })->name('principal')
    ->middleware(EnsureDeviceSessionExists::class);

    Route::post('/cambiar-plan', [UserController::class, 'cambiarPlan'])
    ->name('cambiar.plan');

    Route::get('logout', [LoginController::class, 'logout'])
    ->name('logout');

    Route::get('user', [LoginController::class, 'user'])
        ->name('user')
        ->middleware(EnsureDeviceSessionExists::class);

    Route::get('movies', [MovieController::class, 'index'])
        ->name('movies')
        ->middleware(EnsureDeviceSessionExists::class);

    Route::get('movies/{id}', [MovieController::class, 'show'])
        ->name('movies.show')
        ->middleware(EnsureDeviceSessionExists::class);

    Route::get('device-name', [UserSessionController::class, 'showForm'])
        ->name('device.name.form');
    
    Route::post('device-name', [UserSessionController::class, 'store'])
        ->name('device.name.store');

    Route::get('manage-devices', [UserSessionController::class, 'manage'])
        ->name('manage.devices');

    Route::delete('remove-devices/{device}', [UserSessionController::class, 'destroy'])
        ->name('devices.destroy');

    Route::get('planes', function () {
        return view('plans.index');
    })
        ->name('planes');
    
    Route::get('planes-2', function () {
        return view('plans.index-2');
    })
        ->name('planes.2');

    Route::get('/verify-device', [UserController::class, 'verifyDevice'])
        ->name('verify.device');

    Route::post('/verify-device', [UserController::class, 'validateDevice']);

});

Route::get('login', [LoginController::class, 'loginForm'])
    ->name('loginForm');

Route::post('login', [LoginController::class, 'login'])
    ->name('login');
