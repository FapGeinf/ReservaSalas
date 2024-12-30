<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Sala;

class HomeController extends Controller
{
    public function index()
    {


        {
            $events = [];
     
            $reservas = Reserva::with(['sala'])->get();
     
            foreach ($reservas as $reserva) {
                $events[] = [
                    'title' => $reserva->sala->name . '('.$reserva->sala->name.')',
                    'start' => $reserva->data_inicio,
                    'end' => $reserva->data_fim,
                ];
            }
            return view('home', compact('events'));
        }
    }
}
