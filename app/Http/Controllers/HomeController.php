<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use App\Models\Reserva;

class HomeController extends Controller
{
    public function index()
    {
        // Buscar todas as salas
        $salas = Sala::all();

        // Buscar todas as reservas
        $reservas = Reserva::with('sala')->get();

        // Retornar a view com os dados
        return view('home', compact('salas', 'reservas'));
    }
}

