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
        $users = User::all(); // Uso do modelo User
        $reservas = Reserva::with('sala')->get(); // Carrega as reservas com suas salas
        $salas = Sala::all(); // Carrega as salas para o formulário
        return view('reservas.index', compact('reservas', 'salas', 'users'));
    }

    public function create()
    {
        $salas = Sala::all();
        $users = User::all();
        $reservas = Reserva::with('sala')->get(); // Carrega as reservas e suas relações com as salas
        return view('reservas.create', compact('salas', 'reservas', 'users'));
    }

    public function store(Request $request)
    {     
        try {
            // Validação dos dados do formulário
            $request->validate([
                'sala_fk' => 'required|exists:salas,id',
                'data_reserva' => 'required|date',
                'hora_inicio' => 'required|date_format:H:i',
                'hora_termino' => 'required|date_format:H:i|after:hora_inicio',
            ]);

            // Dados da reserva
            $salaId = $request->input('sala_fk');
            $dataInicio = $request->input('data_reserva') . ' ' . $request->input('hora_inicio');
            $dataFim = $request->input('data_reserva') . ' ' . $request->input('hora_termino');

            // Verifica conflitos de horários
            $conflito = Reserva::where('sala_fk', $salaId)
                ->where(function ($query) use ($dataInicio, $dataFim) {
                    $query->whereBetween('data_inicio', [$dataInicio, $dataFim])
                          ->orWhereBetween('data_fim', [$dataInicio, $dataFim])
                          ->orWhere(function ($query) use ($dataInicio, $dataFim) {
                              $query->where('data_inicio', '<=', $dataInicio)
                                    ->where('data_fim', '>=', $dataFim);
                          });
                })
                ->exists();

            if ($conflito) {
                // Redireciona com mensagem de erro
                return redirect()->back()->with('error', 'A sala já está reservada nesse horário.');
            }

            // Cria a reserva
            Reserva::create([
                'sala_fk' => $salaId,
                'data_inicio' => $dataInicio,
                'data_fim' => $dataFim,
            ]);

            // Redireciona com mensagem de sucesso
            return redirect()->route('reservas.index')->with('success', 'Reserva criada com sucesso!');
        } catch (\Exception $e) {
            // Depuração e redirecionamento em caso de erro
            return redirect()->back()->with('error', 'Ocorreu um erro ao tentar criar a reserva: ' . $e->getMessage());
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
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after:data_inicio',
        ]);

        // Verificar conflitos de horários
        $conflito = Reserva::where('sala_fk', $request->input('sala_id'))
            ->where('id', '!=', $reserva->id) // Ignorar a reserva atual ao verificar conflitos
            ->where(function ($query) use ($request) {
                $query->whereBetween('data_inicio', [$request->input('data_inicio'), $request->input('data_fim')])
                      ->orWhereBetween('data_fim', [$request->input('data_inicio'), $request->input('data_fim')])
                      ->orWhere(function ($query) use ($request) {
                          $query->where('data_inicio', '<=', $request->input('data_inicio'))
                                ->where('data_fim', '>=', $request->input('data_fim'));
                      });
            })
            ->exists();

        if ($conflito) {
            return redirect()->back()->with('error', 'A sala já está reservada neste horário.');
        }

        // Atualizar os dados da reserva
        $reserva->update($request->all());

        return redirect()->route('reservas.index')->with('success', 'Reserva atualizada com sucesso!');
    }

    public function destroy(Reserva $reserva)
    {
        $reserva->delete();
        return redirect()->route('reservas.index');
    }

    // Método personalizado para visualizar uma reserva específica 
    public function view($id) 
    { 
        $reserva = Reserva::findOrFail($id); 
        return view('reservas.view', compact('reserva')); 
    } 
    
    // Método personalizado para cancelar uma reserva específica 
    public function cancel($id) 
    { 
        $reserva = Reserva::findOrFail($id); 
        $reserva->delete(); 
        return redirect()->route('reservas.index')->with('status', 'Reserva cancelada com sucesso!'); 
    } 

    
}
