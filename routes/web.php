<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\SalaReservaController;


   // Redireciona a rota raiz para "home"
    Route::get('/', function () {
    return redirect()->route('home');
}); 

   // Página "Home"
 Route::get('/home', [HomeController::class, 'index'])->name('home');

 Route::get('/', function () {
    return view('welcome');
});

<<<<<<< Updated upstream

    Route::get('/dashboard', function () {
        // return view('welcome'); 
        return redirect('/'); 
    // return view('dashboard');
 })->middleware(['auth', 'verified'])->name('dashboard');

=======
// Página "Home"
Route::get('/home', [HomeController::class, 'home'])->name('home');

// Rotas públicas (fora do middleware auth)
Route::get('/salas', [SalaController::class, 'index'])->name('salas.index'); // Listar Salas
Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index'); // Listar Reservas

// Dashboard (redireciona para "home")
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');
>>>>>>> Stashed changes


    Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
<<<<<<< Updated upstream
     
    Route::resource('salas', SalaController::class);

    Route::get('/home', [ReservaController::class, 'index'])->name('reservas.index');
    Route::resource('reservas', ReservaController::class);
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    
        // Certifique-se de que você está vinculando corretamente
        Route::get('/sala/{sala}', [SalaController::class, 'show']);
        Route::get('/reservas/{reserva}', [ReservaController::class, 'show'])->name('reservas.show');
        
        Route::prefix('reservas')->group(function () {
        Route::get('/', [ReservaController::class, 'index'])->name('reservas.index');
=======

    // Rotas de Salas (CRUD)
    Route::resource('salas', SalaController::class)->except(['index']); // "index" está definido como público
    Route::get('/salas', [SalaController::class, 'index'])->name('salas.index');
    Route::get('/salas', [SalaController::class, 'index'])->name('salas');


    // Rotas de Reservas (CRUD)
    Route::prefix('reservas')->group(function () {
<<<<<<< Updated upstream
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
        Route::get('/create', [ReservaController::class, 'create'])->name('reservas.create');
        Route::post('/', [ReservaController::class, 'store'])->name('reservas.store');
        Route::get('/{reserva}', [ReservaController::class, 'show'])->name('reservas.show');
        Route::get('/{reserva}/edit', [ReservaController::class, 'edit'])->name('reservas.edit');
        Route::put('/{reserva}', [ReservaController::class, 'update'])->name('reservas.update');
        Route::delete('/{reserva}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
    });
 

});
 



require __DIR__.'/auth.php';
