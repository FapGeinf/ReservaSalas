<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\SalaController;

    Route::get('/', function () {
    return view('welcome');
});

    // Route::get('/home', function () { 
    // return view('home'); 
  


    Route::get('/dashboard', function () {
        // return view('welcome'); 
        return redirect('/'); 
    // return view('dashboard');
 })->middleware(['auth', 'verified'])->name('dashboard');



    Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('salas', SalaController::class);

    Route::get('/home', [ReservaController::class, 'index'])->name('reservas.index');
    Route::resource('reservas', ReservaController::class);
    
    
});


require __DIR__.'/auth.php';
