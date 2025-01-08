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

        {
            $events = [];
     
            $reservas = Reserva::with(['salas'])->get();
     
            foreach ($reservas as $reserva) {
                $events[] = [
                    'title' => $reserva->salas->name . '('.$reserva->salas->name.')',
                    'start' => $reserva->data_inicio,
                    'end' => $reserva->data_fim,
                ];
            }
            return view('reservas.index', compact('reservas'));
        }



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

    public function apiReservas()
    {
        $reservas = Reserva::with('sala')->get();

        $events = $reservas->map(function ($reserva) {
            return [
                'title' => $reserva->sala->nome,
                'start' => $reserva->data_inicio,
                'end' => $reserva->data_fim,
            ];
        });

        return response()->json($events);
    }
}


        


