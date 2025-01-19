<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\User;
use App\Models\Sala;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index()
    {
        $users = User::all();
        $reservas = Reserva::with('sala')->get();
        $salas = Sala::all();
        return view('reservas.index', compact('reservas', 'salas'));
    }

    public function create()
    {
        $salas = Sala::all();
        $users = User::all();
        $user = auth()->user();
        $reservas = Reserva::with('sala')->get();
        return view('reservas.create', compact('salas', 'reservas', 'users', 'user'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'sala_fk' => 'required|exists:salas,id',
                'data_reserva' => 'required|date',
                'hora_inicio' => 'required|date_format:H:i',
                'hora_termino' => 'required|date_format:H:i|after:hora_inicio',
            ]);

            Reserva::create([
                'sala_fk' => $request->input('sala_fk'),
                'data_inicio' => $request->input('data_reserva') . ' ' . $request->input('hora_inicio'),
                'data_fim' => $request->input('data_reserva') . ' ' . $request->input('hora_termino'),
            ]);

            return redirect()->route('reservas.index')->with('success', 'Reserva criada com sucesso!');
        } catch (\Exception $th) {
            dd($th);
        }
    }

    public function show(Reserva $reserva)
    {
        return view('reservas.show', compact('reserva'));
    }

    public function edit(Reserva $reserva)
    {
        $salas = Sala::all();
        $users = User::all();
        return view('reservas.edit', compact('reserva', 'salas', 'users'));
    }

    public function update(Request $request, Reserva $reserva)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'sala_id' => 'required|exists:salas,id',
            'data_reserva' => 'required|date',
        ]);

        $reserva->update($request->all());

        return redirect()->route('reservas.index');
    }

    public function destroy(Reserva $reserva)
    {
        $reserva->delete();
        return redirect()->route('reservas.index');
    }

    public function view($id)
    {
        $reserva = Reserva::findOrFail($id);
        return view('reservas.view', compact('reserva'));
    }

    public function cancel($id)
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->delete();
        return redirect()->route('reservas.index')->with('status', 'Reserva cancelada com sucesso!');
    }
}
