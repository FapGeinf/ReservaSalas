<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    ProfileController,
    HomeController,
    ReservaController,
    SalaController,
    UserController,
    Auth\RegisteredUserController
};

Route::get('/', function () {
    return redirect()->route('home');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', fn() => redirect()->route('home'))->name('dashboard');
    Route::get('/user/home', [HomeController::class, 'userHome'])->name('user.home');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    Route::resource('salas', SalaController::class)->names(['index' => 'salas']);

    Route::controller(ReservaController::class)->group(function () {
        Route::get('/reservas/data', 'getReservasPorData');
        Route::get('/reservas/dia/{sala}', 'getReservasDoDia');
        Route::get('/reservas/sala/{salaId}', 'getReservasPorSalaEData');
        Route::get('/eventos', 'getEventos');
        
        Route::prefix('reservas')->name('reservas.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/lista-reunioes','listarReunioes')->name('lista-reunioes');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{reserva}', 'show')->name('show');
            Route::get('/{reserva}/edit', 'edit')->name('edit');
            Route::put('/{reserva}', 'update')->name('update');
            Route::delete('/{reserva}', 'destroy')->name('destroy');
            Route::put('/{reserva}/encerrar', 'encerrar')->name('encerrar');
        });
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/usuarios', 'index')->name('usuarios.index');
        Route::get('/usuarios/create', 'create')->name('usuarios.create');
        Route::post('/usuarios', 'store')->name('usuarios.store');
  
        Route::get('/usuarios/{id}/edit', 'edit')->name('usuarios.edit'); 
        Route::put('/usuarios/{id}', 'update')->name('usuarios.update');
        
        Route::delete('/usuarios/{id}', 'destroy')->name('usuarios.destroy');
        Route::post('/usuario/marcar-tutorial', 'marcarTutorial')->name('usuario.marcarTutorial');
    });

    Route::middleware(['admin'])->group(function () {
        Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');
    });

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});

require __DIR__ . '/auth.php';