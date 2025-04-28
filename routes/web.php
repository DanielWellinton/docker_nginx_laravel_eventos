<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompraIngressoController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\IngressoController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/participante/dashboard', [ParticipanteController::class, 'dashboard'])->name('participante.dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/compraingressos', [CompraIngressoController::class, 'index'])->name('compraingressos.index');
    Route::get('/compraingressos/create', [CompraIngressoController::class, 'create'])->name('compraingressos.create');
    Route::post('/compraingressos', [CompraIngressoController::class, 'store'])->name('compraingressos.store');
    // Route::get('/compraingressos/{id}', [CompraIngressoController::class, 'show'])->name('compraingressos.show');
    // Route::get('/compraingressos/{id}/edit', [CompraIngressoController::class, 'edit'])->name('compraingressos.edit');
    // Route::put('/compraingressos/{id}', [CompraIngressoController::class, 'update'])->name('compraingressos.update');
    // Route::delete('/compraingressos/{id}', [CompraIngressoController::class, 'destroy'])->name('compraingressos.destroy');
    Route::get('/compraingressos/success', [CompraIngressoController::class, 'success'])->name('compraingressos.success');
    Route::get('/compraingressos/cancel', [CompraIngressoController::class, 'cancel'])->name('compraingressos.cancel');


    Route::middleware(['admin'])->group(function () {
        Route::get('/ingressos', [IngressoController::class, 'index'])->name('ingressos.index');
        Route::get('/ingressos/create', [IngressoController::class, 'create'])->name('ingressos.create');
        Route::post('/ingressos', [IngressoController::class, 'store'])->name('ingressos.store');
        Route::get('/ingressos/{id}', [IngressoController::class, 'show'])->name('ingressos.show');
        Route::get('/ingressos/{id}/edit', [IngressoController::class, 'edit'])->name('ingressos.edit');
        Route::put('/ingressos/{id}', [IngressoController::class, 'update'])->name('ingressos.update');
        Route::delete('/ingressos/{id}', [IngressoController::class, 'destroy'])->name('ingressos.destroy');

        Route::get('/eventos', [EventoController::class, 'index'])->name('eventos.index');
        Route::get('/eventos/create', [EventoController::class, 'create'])->name('eventos.create');
        Route::post('/eventos', [EventoController::class, 'store'])->name('eventos.store');
        Route::get('/eventos/{id}', [EventoController::class, 'show'])->name('eventos.show');
        Route::get('/eventos/{id}/edit', [EventoController::class, 'edit'])->name('eventos.edit');
        Route::put('/eventos/{id}', [EventoController::class, 'update'])->name('eventos.update');
        Route::delete('/eventos/{id}', [EventoController::class, 'destroy'])->name('eventos.destroy');

        Route::get('/ingressos/{id}/precos', [PriceController::class, 'index']);
        Route::post('/precos', [PriceController::class, 'store']);
        Route::post('/precos/{id}/ativar', [PriceController::class, 'update']);

    });
});


require __DIR__.'/auth.php';
