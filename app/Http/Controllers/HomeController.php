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
        $salas = Sala::all();
				$today = \Carbon\Carbon::today();
        $reservas = Reserva::with('sala')
					->whereDate('data_inicio','>=', $today)
					->orderBy('data_inicio', 'asc')
					->get();
        return view('home', compact('salas', 'reservas'));
    }

    public function adminHome()
    {
        $salas = Sala::all();
				$today = \Carbon\Carbon::today();
        $reservas = Reserva::with('sala')
					->whereDate('data_inicio','>=', $today)
					->orderBy('data_inicio', 'asc')
					->get();
        return view('home', compact('salas', 'reservas'));
    }

    public function userHome()
    {
        $salas = Sala::all();
				$today = \Carbon\Carbon::today();
        $reservas = Reserva::with('sala')
					->whereDate('data_inicio','>=', $today)
					->orderBy('data_inicio', 'asc')
					->get();

        return view('home.user', compact('salas', 'reservas'));
    }
}

