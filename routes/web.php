<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\SalaController;
use Illuminate\Support\Facades\Route;

// Rota raiz redireciona para "home"
Route::get('/', function () {
    return redirect()->route('home');
});

// Página "Home"
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Rotas públicas (fora do middleware auth)
Route::view('/salas', 'salas')->name('salas'); // View de Salas
Route::view('/reservas', 'reservas')->name('reservas'); // View de Reservas

// Dashboard (redireciona para a raiz)
Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rotas protegidas por middleware "auth"
Route::middleware('auth')->group(function () {
    // Rotas de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas de Salas (CRUD)
    Route::resource('salas', SalaController::class);
    Route::get('/', [SalaController::class, 'index'])->name('salas');

    // Rotas de Reservas (CRUD)
    Route::prefix('reservas')->group(function () {
        Route::get('/', [ReservaController::class, 'index'])->name('reservas.index');
        Route::get('/create', [ReservaController::class, 'create'])->name('reservas.create');
        Route::post('/', [ReservaController::class, 'store'])->name('reservas.store');
        Route::get('/{reserva}', [ReservaController::class, 'show'])->name('reservas.show');
        Route::get('/{reserva}/edit', [ReservaController::class, 'edit'])->name('reservas.edit');
        Route::put('/{reserva}', [ReservaController::class, 'update'])->name('reservas.update');
        Route::delete('/{reserva}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
    });
});



require __DIR__.'/auth.php';
