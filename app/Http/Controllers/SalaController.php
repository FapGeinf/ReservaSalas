<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalaRequest;
use App\Models\Sala;
use App\Services\ReservaService;
use App\Services\SalaService;
use App\Services\UserService;
use Illuminate\Http\Request;

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
        $salas = $this->salaService->getSalas();
        $reservas = $this->reservaService->getReservas();

        return view('salas.index', compact('salas', 'reservas'));
    }

    public function create()
    {
        return view('salas.create');
    }

    public function store(SalaRequest $request)
    {

        $this->salaService->createSala($request->validated());
        return redirect()->route('salas')->with('success', 'Sala criada com sucesso!');
    }

    public function show(Sala $sala)
    {
        return view('salas.show', compact('sala'));
    }

    public function edit(Sala $sala)
    {
        return view('salas.edit', compact('sala'));
    }

    public function update(SalaRequest $request, Sala $sala)
    {
        $this->salaService->updateSala($sala, $request->validated());
        return redirect()->back()->with('success', 'Sala atualizada com sucesso!');
    }

    public function destroy(Sala $sala)
    {
        $this->salaService->deleteSala($sala);
        return redirect()->back()->with('success', 'Sala excluída com sucesso!');
    }
}
