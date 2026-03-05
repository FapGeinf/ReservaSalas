<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservaRequest;
use App\Http\Requests\UpdateReservaRequest;
use App\Models\Reserva;
use App\Models\User;
use App\Models\Sala;
use App\Models\Unidade;
use Illuminate\Http\Request;
use App\Services\ReservaService;
use App\Services\UserService;
use App\Services\SalaService;
use App\Services\UnidadeService;
use Carbon\Carbon;
use Exception;

class ReservaController extends Controller
{
    protected $reservaService, $userService, $salaService, $unidadeService;

    public function __construct(ReservaService $reservaService, UserService $userService, SalaService $salaService, UnidadeService $unidadeService)
    {
        $this->reservaService = $reservaService;
        $this->userService = $userService;
        $this->salaService = $salaService;
        $this->unidadeService = $unidadeService;
    }

    public function index()
    {
        $users = $this->userService->getUsers();
        $unidades = $this->unidadeService->getUnidades();
        $reservas = $this->reservaService->getReservasOrderByData();
        $salas = Sala::all();
        return view('home', compact('reservas','unidades', 'salas', 'users'));
    }

    public function create()
    {
        $salas = $this->salaService->getSalas();
        $users = $this->userService->getUsers();
        $reservas = $this->reservaService->getReservas();
        return view('reservas.create', compact('salas', 'reservas', 'users'));
    }


    public function store(StoreReservaRequest $request)
    {
        try {
            $reserva = $this->reservaService->criarReserva($request->validated());

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'reserva' => $reserva,
                    'redirect' => route('home'),
                    'message' => 'Reserva realizada com sucesso!'
                ]);
            }

            return redirect()->route('home')->with('success', 'Reserva realizada com sucesso!');

        } catch (Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
            }

            return back()->with('Desculpe!', $e->getMessage())->withInput();
        }
    }

    public function show(Reserva $reserva)
    {
        return view('reservas.show', compact('reserva'));
    }
    
    public function edit(Reserva $reserva)
    {
        $user = auth()->user();
        $unidades = $this->unidadeService->getUnidades();

        if (!($user->is_admin || $user->id === $reserva->user_id)) {
            return back()->with(
                'error',
                'Este perfil de usuário não tem permissão para editar esta reserva.'
            );
        }
        if(!session()->has('return_url')){
            session(['return_url' => url()->previous()]);
        }
        $salas = $this->salaService->getSalas();
        return view('reservas.edit', compact('reserva','salas','unidades'));
    }

    public function update(UpdateReservaRequest $request, Reserva $reserva)
    {
        try {
            $this->reservaService->atualizarReserva($reserva, $request->validated());

            $returnUrl = session()->pull('return_url', route('reservas.index'));

            return redirect($returnUrl)->with('success', 'Reserva atualizada com sucesso!');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Erro ao atualizar: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function encerrar(Reserva $reserva){
        $user = auth()->user();
        if(!($user->is_admin || $user->id === $reserva->user_id)){
            return back()->with('error', 'Desculpe, este perfil de usuário não tem permissão para encerrar esta reserva.');
        }
        try{
            $now = Carbon::now();
            
            // Só verifica se já passou do fim
            if (Carbon::parse($reserva->data_fim)->lt($now)) {
                return back()->with('error', 'Esta reserva já foi encerrada.');
            }
            
            $reserva->update(['data_fim' => $now]);
            return back()->with('success', 'Reserva finalizada');
            
        }catch(\Exception $e){
            return back()->with('error', 'Erro: ' . $e->getMessage());
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
                        'sala_fk' => $reserva->sala_fk,
                        'unidade_fk' => $reserva->unidade_fk,
                        'responsavel' => $reserva->user->name,
                        'finalidade' => $reserva->finalidade
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