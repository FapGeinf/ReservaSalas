<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index()
    {
        $reservas = Reserva::with('user', 'sala')->get();
        return view('reservas.index', compact('reservas'));
}
     public function create()
     {
        $salas = Sala::all();
        $users = User::all();
        return view('reservas.create', compact('salas','users'));
     }

     public function store(Request $request)
        {
            $request->validate([
                'user_id'=>'required|exists:users, id',
                'sala_id' =>'required|exists:salas, id',
                'data_reserva'=>'required|date',

            ]);
            
            $reserva = Reserva::create($request->all());
            return redirect()->route('reservas.index');
        }
        public function show(Reserva $reserva)
        {
            return view('reservas.show', compact('reserva'));
        }
        public function edit(Reserva $reserva)
        {
            $salas = Sala::all();
            $users = User::all();
            return view('reservas.edit', compact('reserva','salas','users'));
        }

        public function update(Request $request, Reserva $reserva)
        {
            $request->validate([
                'user_id'=> 'required|exists:users, id',
                'sala_id'=> 'required|exists:salas, id',
                'data_reserva'=> 'required|date',
                ]);
                $reserva->update($request->all());
                return redirect()->route('reservas.index');
        }

         public function destroy(Reserva $reserva)
           {
            $reserva->delete();
            return redirect()->route('reservas.index');
           }

}
