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
        $request->validate([
            'sala_fk' => 'required|exists:salas,id',
            'data_reserva' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_termino' => 'required|date_format:H:i|after:hora_inicio',
        ]);
    
        $salaId = $request->input('sala_fk');
        $dataInicio = $request->input('data_reserva') . ' ' . $request->input('hora_inicio');
        $dataFim = $request->input('data_reserva') . ' ' . $request->input('hora_termino');
    
        // Verificar conflitos de horário
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
            if ($request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'A sala já está reservada neste horário.'
                ], 400);
            }
            return back()->with('error', 'A sala já está reservada neste horário.');
        }
    
        // Criar a reserva
        $reserva = Reserva::create([
            'sala_fk' => $salaId,
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'user_id' => auth()->id(),
            'unidade_fk' => auth()->user()->unidade_fk,
        ]);
    
        // Resposta para requisições AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'reserva' => $reserva,
                'redirect' => route('home'),
                'message' => 'Reserva realizada com sucesso!'
            ]);
        }
    
        // Resposta para requisições normais
        return redirect()->route('home')->with('success', 'Reserva realizada com sucesso!');
    }


    public function show(Reserva $reserva)
    {
        return view('reservas.show', compact('reserva'));
    }



    public function edit(Reserva $reserva)
    {
        // Verifica se o usuário é admin OU se a reserva pertence a ele
        if (auth()->user()->role !== 'admin' && auth()->user()->id !== $reserva->user_id) {
            return redirect()->route('home')->with('error', 'Você não tem permissão para editar esta reserva.');
        }
    
        $salas = Sala::all(); 
        $users = User::all(); 
        return view('reservas.edit', compact('reserva', 'salas', 'users'));
    }
    
    public function update(Request $request, Reserva $reserva)
    {
        // Bloqueia se o usuário não for admin e não for o dono da reserva
        if (auth()->user()->role !== 'admin' && auth()->user()->id !== $reserva->user_id) {
            return redirect()->route('home')->with('error', 'Você não tem permissão para alterar esta reserva.');
        }
    
        $request->validate([
            'sala_id' => 'required|exists:salas,id',
            'data_inicio' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'data_fim' => 'required|date_format:H:i|after:hora_inicio',
        ]);
    
        $reserva->update([
            'sala_fk' => $request->input('sala_id'),
            'data_inicio' => $request->input('data_inicio') . ' ' . $request->input('hora_inicio'),
            'data_fim' => $request->input('data_inicio') . ' ' . $request->input('data_fim'),
        ]);
    
        return redirect()->route('home')->with('success', 'Reserva atualizada com sucesso!');
    }
    
    public function destroy(Reserva $reserva)
    {
        // Permite que apenas administradores ou o próprio usuário excluam a reserva
        if (auth()->user()->role !== 'admin' && auth()->user()->id !== $reserva->user_id) {
            return redirect()->route('home')->with('error', 'Você não tem permissão para excluir esta reserva.');
        }
    
        try {
            $reserva->delete();
            return redirect()->route('home')->with('success', 'Reserva excluída com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Erro ao excluir a reserva.');
        }
    }


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


public function getReservasPorSalaEData($salaId, Request $request)
{
    $data = $request->query('data'); // Obtém a data da requisição

    // Busca as reservas da sala para a data especificada
    $reservas = Reserva::where('sala_fk', $salaId)
        ->whereDate('data_inicio', $data)
        ->with(['user', 'user.unidade'])
        ->get();

    return response()->json($reservas);
}


public function eventos()
{
    $reservas = Reserva::with(['sala', 'user.unidade'])->get();
    
    $events = [];
    foreach ($reservas as $reserva) {
        $events[] = [
            'title' => $reserva->sala->nome,
            'start' => $reserva->data_inicio,
            'end' => $reserva->data_fim,
            'extendedProps' => [
                'unidade' => $reserva->user->unidade->nome ?? 'Sem unidade',
                'hora_inicio' => Carbon::parse($reserva->data_inicio)->format('H:i'),
                'hora_fim' => Carbon::parse($reserva->data_fim)->format('H:i'),
                'responsavel' => $reserva->user->name
            ],
            'color' => '#3788d8', // Cor opcional para o evento
            'textColor' => '#ffffff' // Cor do texto
        ];
    }
    
    return response()->json($events);
}



}

