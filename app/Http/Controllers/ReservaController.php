<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservaRequest;
use App\Http\Requests\UpdateReservaRequest;
use App\Models\Reserva;
use Illuminate\Http\Request;
use App\Services\ReservaService;
use App\Services\UserService;
use App\Services\SalaService;
use App\Services\UnidadeService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    protected $reservaService, $userService, $salaService, $unidadeService;

    public function __construct(
        ReservaService $reservaService,
        UserService $userService,
        SalaService $salaService,
        UnidadeService $unidadeService
    ) {
        $this->reservaService = $reservaService;
        $this->userService = $userService;
        $this->salaService = $salaService;
        $this->unidadeService = $unidadeService;
    }

    /**
     * Lista todas as reservas com permissão:
     * - Admin: vê todas
     * - Comum: vê apenas as suas + unidade
     */
    public function index()
    {
        try {
            $users = $this->userService->getUsers();
            $unidades = $this->unidadeService->getUnidades();
            $salas = $this->salaService->getSalasWhereIsActive();

            // Usa o novo método unificado com filtro de permissão automático
            $reservas = $this->reservaService->getAllReservas();

            return view('home', compact('reservas', 'unidades', 'salas', 'users'));
        } catch (Exception $e) {
            Log::error('Erro ao carregar a página inicial: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao carregar o painel de reservas.');
        }
    }

    /**
     * Formulário de criação de reserva
     */
    public function create()
    {
        try {
            $salas = $this->salaService->getSalasWhereIsActive();
            $users = $this->userService->getUsers();
            // Busca todas as reservas (sem filtro de permissão, pois é para verificação de disponibilidade)
            $reservas = $this->reservaService->getAllReservas(false);
            return view('reservas.create', compact('salas', 'reservas', 'users'));
        } catch (Exception $e) {
            Log::error('Erro ao carregar tela de criação de reserva: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('home')->with('error', 'Erro ao carregar o formulário de reserva.');
        }
    }

    /**
     * Armazena nova reserva
     */
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

            return back()->with('error', 'Erro ao inserir nova reserva: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Exibe detalhes de uma reserva
     */
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

    /**
     * Formulário de edição de reserva
     */
    public function edit(Reserva $reserva)
    {
        try {
            $user = auth()->user();
            $unidades = $this->unidadeService->getUnidades();

            // Permissão: admin ou dono da reserva
            if (!($user->is_admin || $user->id === $reserva->user_id)) {
                Log::warning('Tentativa não autorizada de editar a reserva ID ' . $reserva->id . ' pelo usuário ID ' . $user->id);
                return back()->with('error', 'Você não tem permissão para editar esta reserva.');
            }

            if (!session()->has('return_url')) {
                session(['return_url' => url()->previous()]);
            }

            $salas = $this->salaService->getSalasWhereIsActive();
            return view('reservas.edit', compact('reserva', 'salas', 'unidades'));
        } catch (Exception $e) {
            Log::error('Erro ao carregar edição da reserva ID ' . $reserva->id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'reserva_id' => $reserva->id
            ]);
            return redirect()->route('home')->with('error', 'Erro ao carregar o formulário de edição.');
        }
    }

    /**
     * Atualiza reserva existente
     */
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

    /**
     * Encerra uma reserva (marca como inativa e atualiza data_fim)
     */
    public function encerrar(Reserva $reserva)
    {
        try {
            $this->reservaService->encerrarReserva($reserva);
            return back()->with('success', 'Reserva finalizada com sucesso.');
        } catch (Exception $e) {
            Log::error('Erro ao encerrar reserva ID ' . $reserva->id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'reserva_id' => $reserva->id
            ]);
            return back()->with('error', 'Erro ao encerrar: ' . $e->getMessage());
        }
    }

    /**
     * Remove uma reserva (exclusão definitiva)
     */
    public function destroy(Reserva $reserva)
    {
        try {
            $this->reservaService->deletarReserva($reserva);
            return back()->with('success', 'Reserva excluída com sucesso.');
        } catch (Exception $e) {
            Log::error('Erro ao excluir reserva ID ' . $reserva->id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'reserva_id' => $reserva->id
            ]);
            return back()->with('error', 'Erro ao excluir: ' . $e->getMessage());
        }
    }

    /**
     * Visualização rápida de uma reserva (pop-up ou modal)
     */
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

    /**
     * Cancela uma reserva (apenas altera status, não exclui)
     */
    public function cancel($id)
    {
        try {
            $this->reservaService->cancelarReserva($id);
            return redirect()
                ->route('reservas.index')
                ->with('success', 'Reserva cancelada com sucesso!');
        } catch (Exception $e) {
            Log::error('Erro ao cancelar reserva ID ' . $id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'reserva_id' => $id
            ]);
            return redirect()->route('reservas.index')->with('error', 'Erro ao cancelar a reserva.');
        }
    }

    /**
     * Busca reservas por sala e data (usado em modals/calendários)
     */
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

    /**
     * Busca reservas por data (usado em calendários)
     */
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

    /**
     * Retorna eventos formatados para FullCalendar (reservas ativas)
     */
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

    /**
     * Lista reuniões em uma view específica
     */
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