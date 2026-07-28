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
use Illuminate\Support\Facades\Log;

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
        try {
            $users = $this->userService->getUsers();
            $unidades = $this->unidadeService->getUnidades();
            $reservas = $this->reservaService->getReservasOrderByData();
            $salas = $this->salaService->getSalasWhereIsActive();
            return view('home', compact('reservas','unidades', 'salas', 'users'));
        } catch (Exception $e) {
            Log::error('Erro ao carregar a página inicial (index de reservas): ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with('error', 'Erro ao carregar o painel de reservas.');
        }
    }

    public function create()
    {
        try {
            $salas = $this->salaService->getSalasWhereIsActive();
            $users = $this->userService->getUsers();
            $reservas = $this->reservaService->getReservas();
            return view('reservas.create', compact('salas', 'reservas', 'users'));
        } catch (Exception $e) {
            Log::error('Erro ao carregar tela de criação de reserva: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('home')->with('error', 'Erro ao carregar o formulário de reserva.');
        }
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
            Log::warning('Falha ao criar reserva: ' . $e->getMessage(), [
                'exception' => $e,
                'data' => $request->all()
            ]);

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
            }

            return back()->with('Erro ao inserir uma nova reserva!', $e->getMessage())->withInput();
        }
    }

    public function show(Reserva $reserva)
    {
        try {
            return view('reservas.show', compact('reserva'));
        } catch (Exception $e) {
            Log::error('Erro ao exibir detalhes da reserva ID ' . $reserva->id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'reserva_id' => $reserva->id
            ]);
            return redirect()->route('home')->with('error', 'Erro ao carregar detalhes da reserva.');
        }
    }
    
    public function edit(Reserva $reserva)
    {
        try {
            $user = auth()->user();
            $unidades = $this->unidadeService->getUnidades();

            if (!($user->is_admin || $user->id === $reserva->user_id)) {
                Log::warning('Tentativa não autorizada de editar a reserva ID ' . $reserva->id . ' pelo usuário ID ' . $user->id);
                return back()->with(
                    'error',
                    'Este perfil de usuário não tem permissão para editar esta reserva.'
                );
            }
            if(!session()->has('return_url')){
                session(['return_url' => url()->previous()]);
            }
            $salas = $this->salaService->getSalasWhereIsActive();
            return view('reservas.edit', compact('reserva','salas','unidades'));
        } catch (Exception $e) {
            Log::error('Erro ao carregar edição da reserva ID ' . $reserva->id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'reserva_id' => $reserva->id
            ]);
            return redirect()->route('home')->with('error', 'Erro ao carregar o formulário de edição.');
        }
    }

    public function update(UpdateReservaRequest $request, Reserva $reserva)
    {
        try {
            $this->reservaService->atualizarReserva($reserva, $request->validated());

            $returnUrl = session()->pull('return_url', route('reservas.index'));

            return redirect($returnUrl)->with('success', 'Reserva atualizada com sucesso!');

        } catch (Exception $e) {
            Log::error('Erro ao atualizar reserva ID ' . $reserva->id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'reserva_id' => $reserva->id,
                'data' => $request->validated()
            ]);
            return back()
                ->with('error', 'Erro ao atualizar: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function encerrar(Reserva $reserva){
        try{
            $this->reservaService->encerrarReserva($reserva);
            return back()->with('success', 'Reserva finalizada');
            
        }catch(Exception $e){
            Log::error('Erro ao encerrar reserva ID ' . $reserva->id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'reserva_id' => $reserva->id
            ]);
            return back()->with('error', 'Erro: ' . $e->getMessage());
        }
    }

    public function destroy(Reserva $reserva)
    {
        try{
            $this->reservaService->deletarReserva($reserva);
            return back()->with('success', 'Reserva excluída com sucesso.');
        }catch(Exception $e){
            Log::error('Erro ao excluir reserva ID ' . $reserva->id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'reserva_id' => $reserva->id
            ]);
            return back()->with('error', 'Este perfil de usuario não tem permissão para excluir esta reserva.');
        }
    }

    public function view($id)
    {
        try {
            $reserva = $this->reservaService->buscarReserva($id);
            return view('reservas.view', compact('reserva'));
        } catch (Exception $e) {
            Log::error('Erro ao visualizar reserva ID ' . $id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'reserva_id' => $id
            ]);
            return redirect()->route('home')->with('error', 'Erro ao visualizar a reserva.');
        }
    }

    public function cancel($id)
    {
        try {
            $this->reservaService->cancelarReserva($id);

            return redirect()
                ->route('reservas.index')
                ->with('status', 'Reserva cancelada com sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao cancelar reserva ID ' . $id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'reserva_id' => $id
            ]);
            return redirect()->route('reservas.index')->with('error', 'Erro ao cancelar a reserva.');
        }
    }

    public function getReservasPorSalaEData($salaId, Request $request)
    {
        try {
            $reservas = $this->reservaService
                ->getReservasPorSalaEData($salaId, $request->query('data'));

            return response()->json($reservas);
        } catch (Exception $e) {
            Log::error('Erro ao buscar reservas por sala e data (Sala ID: ' . $salaId . '): ' . $e->getMessage(), [
                'exception' => $e,
                'sala_id' => $salaId,
                'data' => $request->query('data')
            ]);
            return response()->json(['error' => 'Erro ao buscar reservas.'], 500);
        }
    }

    public function getReservasPorData(Request $request)
    {
        try {
            $reservas = $this->reservaService
                ->getReservasPorData($request->input('data'));

            return response()->json($reservas);
        } catch (Exception $e) {
            Log::error('Erro ao buscar reservas por data: ' . $e->getMessage(), [
                'exception' => $e,
                'data' => $request->input('data')
            ]);
            return response()->json(['error' => 'Erro ao buscar reservas.'], 500);
        }
    }

    public function getEventos()
    {
        try {
            $events = $this->reservaService->getEventos();

            return response()->json($events);
        } catch (Exception $e) {
            Log::error('Erro ao buscar eventos: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return response()->json(['error' => 'Erro ao carregar eventos.'], 500);
        }
    }

    public function listarReunioes()
    {
        try {
            $reservas = $this->reservaService->listarReunioes();

            return view('reservas.reservas', compact('reservas'));
        } catch (Exception $e) {
            Log::error('Erro ao listar reuniões: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('home')->with('error', 'Erro ao listar reuniões.');
        }
    }
}