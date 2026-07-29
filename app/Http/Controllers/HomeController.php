<?php

namespace App\Http\Controllers;

use App\Services\ReservaService;
use App\Services\SalaService;

class HomeController extends Controller
{
    protected $salaService, $reservaService;

    public function __construct(SalaService $salaService, ReservaService $reservaService)
    {
        $this->salaService = $salaService;
        $this->reservaService = $reservaService;
    }

    /**
     * Página inicial – exibe reservas ativas com permissão automática:
     * - Admin: vê todas
     * - Comum: vê apenas as suas + da unidade
     */
    public function index()
    {
        $salas = $this->salaService->getSalasWhereIsActive();
        $reservas = $this->reservaService->getActiveReservas(); // padrão: aplica permissão

        return view('home', compact('salas', 'reservas'));
    }

    /**
     * Painel do administrador – força visualização de todas as reservas ativas,
     * independentemente do usuário logado (útil se o admin quiser ver tudo).
     */
    public function adminHome()
    {
        $salas = $this->salaService->getSalasWhereIsActive();
        $reservas = $this->reservaService->getActiveReservas(false); // sem filtro de permissão

        return view('home', compact('salas', 'reservas'));
    }

    /**
     * Painel do usuário comum – exibe apenas reservas ativas da sua unidade
     * ou criadas por ele.
     */
    public function userHome()
    {
        $salas = $this->salaService->getSalasWhereIsActive();
        $reservas = $this->reservaService->getActiveReservas(); 

        return view('home.user', compact('salas', 'reservas'));
    }
}