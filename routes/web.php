<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\UserController;

// Página inicial (antes do login)
Route::get('/', function () {
    return view('auth.login');
});

// // Login manual (LDAP)
// Route::post('/login', function (Request $request) {
//     $credentials = $request->only('username', 'password');

//     if (Auth::attempt(['samaccountname' => $credentials['username'], 'password' => $credentials['password']])) {
//         return redirect()->route('home'); // Redireciona para rota nomeada
//     }

//     return back()->withErrors([
//         'username' => 'Usuário ou senha inválidos.',
//     ]);
// });

// Página de login
Route::get('/login', function () {
    return view('login');
})->name('login')->middleware('guest');

// Ação de login
Route::post('/login', function (Request $request) {
    $credentials = $request->only('username', 'password');

    if (Auth::attempt([
        'samaccountname' => $credentials['username'],
        'password' => $credentials['password']
    ])) {
        $request->session()->regenerate();
        return redirect()->intended('home'); // vai para onde queria ir antes do login
    }

    return back()->withErrors([
        'username' => 'Usuário ou senha inválidos.',
    ]);
})->middleware('guest');


// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {

    // Rota principal após login
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Página específica para usuários comuns
    Route::get('/user/home', [HomeController::class, 'userHome'])->name('user.home');

    // Rotas apenas para administradores
    Route::middleware('admin')->group(function () {
        Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');

        // Gerenciamento de usuários
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/cadastrar', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios/salvar', [UserController::class, 'store'])->name('usuarios.store');

        // Gerenciamento de salas
        Route::get('/salas', [SalaController::class, 'index'])->name('salas.index');
        Route::get('/salas/create', [SalaController::class, 'create'])->name('salas.create');
        Route::post('/salas/store', [SalaController::class, 'store'])->name('salas.store');
        Route::get('/salas/{id}/edit', [SalaController::class, 'edit'])->name('salas.edit');
        Route::put('/salas/{id}', [SalaController::class, 'update'])->name('salas.update');
        Route::delete('/salas/{id}', [SalaController::class, 'destroy'])->name('salas.destroy');
    });

    // Reservas (acessível para todos os usuários autenticados)
    Route::get('/reservas', [ReservaController::class, 'listarReunioes'])->name('reservas.index');
    Route::post('/reservas/salvar', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/reservas/{id}/editar', [ReservaController::class, 'edit'])->name('reservas.edit');
    Route::put('/reservas/{id}', [ReservaController::class, 'update'])->name('reservas.update');
    Route::delete('/reservas/{id}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
});

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');



// require __DIR__.'/auth.php';
