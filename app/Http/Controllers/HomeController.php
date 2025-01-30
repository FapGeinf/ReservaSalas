<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sala;
use App\Models\Reserva;

class HomeController extends Controller
{
    public function index()
    {
        // Buscar todas as salas
        $salas = Sala::all();

        // Buscar todas as reservas com relação à sala
        $reservas = Reserva::with('sala')->get();

        // Redirecionar conforme o tipo de usuário
        if (Auth::user()->is_admin) {
            return view('home', compact('salas', 'reservas'));
        } else {
            return view('home.user', compact('salas', 'reservas'));
        }
    }

    public function adminHome()
    {
        // Buscar todas as salas
        $salas = Sala::all();

        // Buscar todas as reservas com relação à sala
        $reservas = Reserva::with('sala')->get();

        return view('home', compact('salas', 'reservas'));
    }

    public function userHome()
    {
        // Buscar todas as salas
        $salas = Sala::all();

        // Buscar todas as reservas com relação à sala
        $reservas = Reserva::with('sala')->get();

        return view('home.user', compact('salas', 'reservas'));
    }
}

