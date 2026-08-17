<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnlaceController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ReciboController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('clientes', ClienteController::class);
    Route::resource('planes', PlanController::class)
        ->parameters(['planes' => 'plan'])
        ->except(['show']);

    Route::post('suscripciones/{suscripcion}/pagos', [PagoController::class, 'store'])
        ->name('suscripciones.pagos.store');

    Route::get('suscripciones/{suscripcion}/enlace', [EnlaceController::class, 'edit'])
        ->name('suscripciones.enlace.edit');
    Route::put('suscripciones/{suscripcion}/enlace', [EnlaceController::class, 'update'])
        ->name('suscripciones.enlace.update');

    Route::get('recibos/{recibo}', [ReciboController::class, 'show'])->name('recibos.show');
});
