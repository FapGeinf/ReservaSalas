<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\User;
use App\Models\Sala;
use Illuminate\Http\Request;

use Carbon\Carbon;


class ReservaController extends Controller
{
    public function index()
    {
        $users = User::all(); // Uso do modelo User
        $reservas = Reserva::with('sala', 'user.unidade')->get(); // Carrega as reservas com suas salas
        $salas = Sala::all(); // Carrega as salas para o formulário
        return view('home', compact('reservas', 'salas', 'users'));
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
    // dd($request);
    $request->validate([
        'sala_fk' => 'required|exists:salas,id',
        'data_reserva' => 'required|date',
        'hora_inicio' => 'required|date_format:H:i',
        'hora_termino' => 'required|date_format:H:i|after:hora_inicio',
    ]);

    $salaId = $request->input('sala_fk');
    $dataInicio = $request->input('data_reserva') . ' ' . $request->input('hora_inicio');
    $dataFim = $request->input('data_reserva') . ' ' . $request->input('hora_termino');

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
        return redirect()->back()->with('error', 'A sala já está reservada neste horário.');
    }

    // Cria a reserva com os campos necessários
    Reserva::create([
        'sala_fk' => $salaId,
        'data_inicio' => $dataInicio,
        'data_fim' => $dataFim,
        'user_id' => auth()->user()->id,
        'unidade_fk' => auth()->user()->unidade_id, // Salva a unidade do usuário
    ]);

    return redirect()->route('reservas.index')->with('success', 'Reserva criada com sucesso!');
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
            'sala_id' => 'required|exists:salas,id',
            'data_inicio' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'data_fim' => 'required|date_format:H:i|after:hora_inicio',
        ]);
    
        // Verificar conflitos de horários
        $dataInicioCompleto = $request->input('data_inicio') . ' ' . $request->input('hora_inicio');
        $dataFimCompleto = $request->input('data_inicio') . ' ' . $request->input('data_fim');
    
        $conflito = Reserva::where('sala_fk', $request->input('sala_id'))
            ->where('id', '!=', $reserva->getKey()) // Ignorar a reserva atual ao verificar conflitos
            ->where(function ($query) use ($dataInicioCompleto, $dataFimCompleto) {
                $query->whereBetween('data_inicio', [$dataInicioCompleto, $dataFimCompleto])
                      ->orWhereBetween('data_fim', [$dataInicioCompleto, $dataFimCompleto])
                      ->orWhere(function ($query) use ($dataInicioCompleto, $dataFimCompleto) {
                          $query->where('data_inicio', '<=', $dataInicioCompleto)
                                ->where('data_fim', '>=', $dataFimCompleto);
                      });
            })
            ->exists();
    
        if ($conflito) {
            return redirect()->back()->with('error', 'A sala já está reservada neste horário.');
        }
    
        // Atualizar os dados da reserva
        $reserva->update([
            'sala_fk' => $request->input('sala_id'),
            'data_inicio' => $dataInicioCompleto,
            'data_fim' => $dataFimCompleto,
        ]);
    
        return redirect()->route('home')->with('success', 'Reserva atualizada com sucesso!');
    }
    

    public function destroy(Reserva $reserva)
{
    try {
        $reserva->delete();
        return redirect()->route('home')->with('success', 'Reserva excluída com sucesso!');
    } catch (\Exception $e) {
        return redirect()->route('home')->with('error', 'Erro ao excluir a reserva.');
    }
}

    
        // $reserva->delete();
        // return redirect()->route('reservas.index');
    
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


public function getReservasDoDia($salaId)
{
    $hoje = Carbon::now()->toDateString(); // Pega a data atual no formato YYYY-MM-DD

    $reservas = Reserva::where('sala_fk', $salaId)
        ->whereDate('data_inicio', $hoje)
        ->with('user.unidade')
        ->get();

    return response()->json($reservas);
}



}

