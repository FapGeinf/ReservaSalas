<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;

class HomeController extends Controller
{
    public function home()
    {
        // // Busca todas as reservas de salas
        // $reservas = Reserva::with('sala', 'usuario')->get();

        // // Retorna a view 'home' com as reservas
        // return view('home', compact('reservas'));
        return view('home'); 
    }
}

