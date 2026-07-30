<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalaRequest;
use App\Models\Sala;
use App\Services\ReservaService;
use App\Services\SalaService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SalaController extends Controller
{
    protected $salaService;

    protected $reservaService;

    protected $userService;

    public function __construct(SalaService $salaService, ReservaService $reservaService, UserService $userService)
    {
        $this->salaService = $salaService;
        $this->reservaService = $reservaService;
        $this->userService = $userService;
    }

    public function index()
    {
        try {
            $salas = $this->salaService->getSalas();
            $reservas = $this->reservaService->getActiveReservas();

            return view('salas.index', compact('salas', 'reservas'));
        } catch (\Exception $e) {
            Log::error('Erro ao listar salas e reservas: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with('error', 'Erro ao carregar a listagem de salas e reservas.');
        }
    }

    public function create()
    {
        try {
            $salas = $this->salaService->getSalas();
            $users = $this->userService->getUsers();
            return view('reservas.create', compact('salas', 'users'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar tela de cadastro de reserva/sala: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('salas')->with('error', 'Erro ao carregar o formulário.');
        }
    }

    public function store(SalaRequest $request)
    {
        try {
            $this->salaService->createSala($request->validated());
            return redirect()->route('salas')->with('success', 'Sala criada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao criar sala: ' . $e->getMessage(), [
                'exception' => $e,
                'data' => $request->validated()
            ]);
            return redirect()->back()->withInput()->with('error', 'Ocorreu um erro ao criar a sala.');
        }
    }

    public function show(Sala $sala)
    {
        try {
            return view('salas.show', compact('sala'));
        } catch (\Exception $e) {
            Log::error('Erro ao exibir sala ID ' . $sala->id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'sala_id' => $sala->id
            ]);
            return redirect()->route('salas')->with('error', 'Erro ao carregar os detalhes da sala.');
        }
    }

    public function edit(Sala $sala)
    {
        try {
            return view('salas.edit', compact('sala'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar edição da sala ID ' . $sala->id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'sala_id' => $sala->id
            ]);
            return redirect()->route('salas')->with('error', 'Erro ao carregar o formulário de edição da sala.');
        }
    }

    public function update(SalaRequest $request, Sala $sala)
    {
        try {
            $this->salaService->updateSala($sala, $request->validated());
            return redirect()->back()->with('success', 'Sala atualizada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar sala ID ' . $sala->id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'sala_id' => $sala->id,
                'data' => $request->validated()
            ]);
            return redirect()->back()->withInput()->with('error', 'Ocorreu um erro ao atualizar a sala.');
        }
    }

    public function destroy(Sala $sala)
    {
        try {
            $this->salaService->deleteSala($sala);
            return redirect()->back()->with('success', 'Sala excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir sala ID ' . $sala->id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'sala_id' => $sala->id
            ]);
            return redirect()->back()->with('error', 'Erro ao excluir a sala.');
        }
    }
}