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
        $salas = Sala::where('situacao','ativa')->get();
        $today = \Carbon\Carbon::today();
        
        $reservas = Reserva::with(['sala', 'unidade', 'user.unidade'])
         ->whereDate('data_inicio','>=', $today)
         ->orderBy('data_inicio', 'asc')
         ->get();
         
        return view('home', compact('salas', 'reservas'));
    }

    public function adminHome()
    {
        $salas = Sala::where('situacao','ativa')->get();
        $today = \Carbon\Carbon::today();
        
        $reservas = Reserva::with(['sala', 'unidade', 'user.unidade'])
         ->whereDate('data_inicio','>=', $today)
         ->orderBy('data_inicio', 'asc')
         ->get();
         
        return view('home', compact('salas', 'reservas'));
    }

    public function userHome()
    {
        $salas = Sala::where('situacao','ativa')->get();
        $today = \Carbon\Carbon::today();
        
        $reservas = Reserva::with(['sala', 'unidade', 'user.unidade'])
         ->whereDate('data_inicio','>=', $today)
         ->orderBy('data_inicio', 'asc')
         ->get();

        return view('home.user', compact('salas', 'reservas'));
    }
}