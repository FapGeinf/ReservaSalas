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
        try{
            $this->reservaService->encerrarReserva($reserva);
            return back()->with('success', 'Reserva finalizada');
            
        }catch(\Exception $e){
            return back()->with('error', 'Erro: ' . $e->getMessage());
        }
    }


    public function destroy(Reserva $reserva)
    {
        try{
            $this->reservaService->deletarReserva($reserva);
            return back()->with('success', 'Reserva excluída com sucesso.');
        }catch(Exception $e){
            return back()->with('error', 'Este perfil de usuario não tem permissão para excluir esta reserva.');
        }
    }

     public function view($id)
    {
        $reserva = $this->reservaService->buscarReserva($id);

        return view('reservas.view', compact('reserva'));
    }

    public function cancel($id)
    {
        $this->reservaService->cancelarReserva($id);

        return redirect()
            ->route('reservas.index')
            ->with('status', 'Reserva cancelada com sucesso!');
    }

    public function getReservasPorSalaEData($salaId, Request $request)
    {
        $reservas = $this->reservaService
            ->getReservasPorSalaEData($salaId, $request->query('data'));

        return response()->json($reservas);
    }

    public function getReservasPorData(Request $request)
    {
        $reservas = $this->reservaService
            ->getReservasPorData($request->input('data'));

        return response()->json($reservas);
    }

    public function getEventos()
    {
        $events = $this->reservaService->getEventos();

        return response()->json($events);
    }

    public function listarReunioes()
    {
        $reservas = $this->reservaService->listarReunioes();

        return view('reservas.reservas', compact('reservas'));
    }


}