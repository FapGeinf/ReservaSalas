<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\SalaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UserController;

Route::middleware('auth')->group(function () {
    // Página "Home"
    Route::get('/home', [HomeController::class, 'index'])->name('home');


   // Rotas acessíveis por todos os usuários autenticados
    Route::middleware(['auth'])->group(function () {
      Route::get('/home', [HomeController::class, 'index'])->name('home');
      Route::get('/user/home', [HomeController::class, 'userHome'])->name('user.home');
});

// Rotas exclusivas para administradores
    Route::middleware(['auth', 'admin'])->group(function () {
       Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');
});
    // Dashboard (redireciona para a raiz)
    Route::get('/dashboard', function () {
        return redirect('home');
    })->middleware('verified')->name('dashboard');

    // Rotas de perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas de Salas (CRUD)
    Route::resource('salas', SalaController::class)->names([
        'index' => 'salas', // Nome personalizado para a rota index
    ]);

    

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

// Rota raiz redireciona para "home"
Route::get('/', function () {
    return redirect()->route('home');
});

// Rotas de registro
Route::get('register', [RegisteredUserController::class, 'create'])->name('register'); 
Route::post('register', [RegisteredUserController::class, 'store']);

// Rota de logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login'); // Redirecione para a página de login ou outra página desejada
})->name('logout');

Route::get('/reservas/dia/{sala}', [ReservaController::class, 'getReservasDoDia']);

Route::get('/reservas/sala/{salaId}', [ReservaController::class, 'getReservasPorSalaEData']);



// Rotas de usuários

    Route::get('/usuarios', [RegisteredUserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/{id}/edit', [RegisteredUserController::class, 'edit'])->name('usuarios.edit');
    Route::patch('/usuarios/{id}', [RegisteredUserController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [RegisteredUserController::class, 'destroy'])->name('usuarios.destroy');
    // Rota para exibir o formulário de cadastro de usuários
    Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create'); 
    // Rota para processar o cadastro de usuários
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
  

    
    // Rotas de fullcalendar
   
    //  Route::get('/eventos', [ReservaController::class, 'getEventos']);
     Route::post('/reservas/store', [ReservaController::class, 'store'])->name('reservas.store');
     Route::get('/eventos', [ReservaController::class, 'eventos']);

     Route::get('/reservas/sala/{salaId}', [ReservaController::class, 'getReservasPorSalaEData']);

    
     Route::post('/reservas/store', [ReservaController::class, 'store'])->name('reservas.store');
// Inclusão das rotas de autenticação
require __DIR__ . '/auth.php';
