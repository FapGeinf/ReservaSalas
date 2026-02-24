<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\User;
use App\Models\Sala;
use App\Models\Unidade;
use Illuminate\Http\Request;

use Carbon\Carbon;


class ReservaController extends Controller
{
    public function index()
    {
        $users = User::all();
        $unidades = Unidade::all();
        $reservas = Reserva::with('sala', 'user.unidade')
        ->orderBy('data_inicio', 'desc') 
        ->get();
        $salas = Sala::all();
        return view('home', compact('reservas','unidades', 'salas', 'users'));
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
        $dataSelecionada = Carbon::parse($request->input('data_reserva'));
        $request->validate([
            'sala_fk' => 'required|exists:salas,id',
            'data_reserva' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->lt(Carbon::today())) {
                        $fail('A data escolhida deve ser hoje ou uma futura.');
                    }
                },
            ],
            // 'data_reserva' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_termino' => 'required|date_format:H:i|after:hora_inicio ',
        ], [
            'sala_fk.required' => 'Selecione uma sala.',
            'sala_fk.exists' => 'Sala não encontrada.',
            'data_reserva.required' => 'Informe a data da reserva.',
            'data_reserva.after_or_equal' => 'A data escolhida deve ser hoje ou uma futura.',
            'hora_inicio.required' => 'Informe a hora de início.',
            'hora_termino.after' => 'A hora de término deve ser após a hora de início.',
        ]);


        // Verificar se a sala está ativa
        $sala = Sala::findOrFail($request->input('sala_fk'));
        if (strtolower(trim($sala->situacao)) !== 'ativa') {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A sala está em manutenção e não pode ser reservada.'
                ], 400);
            }
            return back()->with('Desculpe!', 'A sala está em manutenção e não pode ser reservada.');
        }


        $salaId = $request->input('sala_fk');
        // $dataInicio = $request->input('data_reserva') . ' ' . $request->input('hora_inicio');
        // $dataFim = $request->input('data_reserva') . ' ' . $request->input('hora_termino');

       $dataInicio = Carbon::parse($request->data_reserva . ' ' . $request->hora_inicio)->format('Y-m-d H:i:s');
       $dataFim    = Carbon::parse($request->data_reserva . ' ' . $request->hora_termino)->format('Y-m-d H:i:s');

       $inicio = Carbon::parse($request->data_reserva . ' ' . $request->hora_inicio);
        $fim    = Carbon::parse($request->data_reserva . ' ' . $request->hora_termino);

        if ($fim->lte($inicio)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'A hora de término deve ser maior que a hora de início.'
                ], 400);
            }

            return back()->withErrors([
                'hora_termino' => 'A hora de término deve ser maior que a hora de início.'
            ])->withInput();
        }


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
            return back()->with('Desculpe!', 'A sala já está reservada neste horário.');
        }

        // Protege a sala Aquário (O retorno do erro deve aparecer na tela)
        // if (str_contains(strtolower($sala->nome), 'aquário')) {
        //     // Verifica se o usuário logado NÃO é admin
        //     if (auth()->user()->role !== 'admin') {
        //         return back()->with('error', 'A sala Aquário só pode ser reservada por administradores para uso de pesquisadores.');
        //     }
        // }

        // Criar a reserva 
        
        $user = auth()->user();
        $unidadeId = $user->is_admin == 1 ? $request->input('unidade_fk') : $user->unidade_fk;
        $reserva = Reserva::create([
            'sala_fk' => $salaId,
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'user_id' => auth()->id(),
            'unidade_fk' => $unidadeId,
            'finalidade' => $request->input('tipo_reserva'), 
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
        $user = auth()->user();

        if (!($user->is_admin || $user->id === $reserva->user_id)) {
            return back()->with(
                'error',
                'Este perfil de usuário não tem permissão para editar esta reserva.'
            );
        }
        if(!session()->has('return_url')){
            session(['return_url' => url()->previous()]);
        }
        $salas = Sala::all();
        return view('reservas.edit', compact('reserva','salas'));
    }

    public function update(Request $request, Reserva $reserva)
    {
        $user = auth()->user();
        if (!($user->is_admin || $user->id === $reserva->user_id)) {
            return back()->with(
                'error',
                'Este perfil de usuário não tem permissão para alterar esta reserva.'
            );
        }
        try{
            $request->validate([
                'sala_id'     => 'required|exists:salas,id',
                'hora_inicio' => 'required|date_format:H:i',
                'data_fim'    => 'required|date_format:H:i',
            ]);

            $inicio = Carbon::parse($request->data_inicio . ' ' . $request->hora_inicio);
            $fim    = Carbon::parse($request->data_inicio . ' ' . $request->data_fim);

            if ($fim->lte($inicio)) {
                return back()
                    ->withErrors(['data_fim' => 'A hora de término deve ser maior que a hora de início.'])
                    ->withInput();
            }

            $reserva->update([
                'sala_fk'     => $request->sala_id,
                'data_inicio' => $inicio,
                'data_fim'    => $fim,
            ]);

            $returnUrl = session()->pull('return_url', route('reservas.index'));

            return redirect($returnUrl)->with('success', 'Reserva atualizada com sucesso!');
        }catch(\Exception $e){
            return back()->with('error', 'Desculpe, não foi possível atualizar a reserva: '. $e->getMessage());
        }
        
    }



    public function destroy(Reserva $reserva)
    {
        $user = auth()->user();
        if($user->is_admin || $user->id === $reserva->user_id){

            $reserva->delete();
            
            return back()->with('success', 'Reserva excluída com sucesso');
        }
        return back()->with('error', 'Este perfil de usuario não tem permissão para excluir esta reserva.');
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

    /**
 * Lista todas as reservas de uma data específica (todas as salas).
 * Rota: GET /reservas/data
 */
public function getReservasPorData(Request $request)
{
    $data = $request->input('data');

    if (!$data) {
        return response()->json([], 200);
    }

    $reservas = \App\Models\Reserva::with(['sala', 'user.unidade'])
        ->whereDate('data_inicio', $data)
        ->orderBy('data_inicio', 'asc')
        ->get();

    return response()->json($reservas);
}


    public function getEventos()
    {
        $reservas = Reserva::with(['sala', 'user.unidade'])->get();
        $now = Carbon::now();

        $events = [];
        foreach ($reservas as $reserva) {
            $isPast = Carbon::parse($reserva->data_fim)->lt($now);

            $color = $reserva->sala->cor ?? '#3788d8';
            $backgroundColor = $isPast ? $this->hexToRgba($color, 0.90) : $color;
            $borderColor = $isPast ? $this->hexToRgba($color, 0.90) : $color;
            $textColor = $isPast ? '#333333' : '#ffffff';

            $events[] = [
                $events[] = [
                    'id' => $reserva->id,
                    // 'title' => $reserva->sala->nome,
                    'title' => $reserva->sala?->nome,
                    'start' => Carbon::parse($reserva->data_inicio)->format('Y-m-d\TH:i:s'),
                    'end' => Carbon::parse($reserva->data_fim)->format('Y-m-d\TH:i:s'),
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $borderColor,
                    'textColor' => $textColor,
                    'extendedProps' => [
                        'unidade' => $reserva->unidade->sigla ?? 'nome da unidade',
                        'hora_inicio' => Carbon::parse($reserva->data_inicio)->format('H:i'),
                        'hora_fim' => Carbon::parse($reserva->data_fim)->format('H:i'),
                        'data_inicio' => Carbon::parse($reserva->data_inicio)->format('Y-m-d'),
                        'sala_id' => $reserva->sala_fk,
                        'responsavel' => $reserva->user->name

                    ]
                ]
            ];
        }

        return response()->json($events);
    }



    private function hexToRgba($hex, $opacity = 1.0)
    {
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) === 3) {
            $r = hexdec(str_repeat(substr($hex, 0, 1), 4));
            $g = hexdec(str_repeat(substr($hex, 1, 1), 4));
            $b = hexdec(str_repeat(substr($hex, 2, 1), 4));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        return "rgba($r, $g, $b, $opacity)";
    }

    public function listarReunioes()
    {
        $reservas = Reserva::with('sala', 'user.unidade')->get();
        return view('reservas.reservas', compact('reservas'));
    }

}