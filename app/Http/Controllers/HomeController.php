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

    public function index()
    {
        $salas = $this->salaService->getSalasWhereIsActive();
        $reservas = $this->reservaService->getReservasFuturas();

        return view('home', compact('salas', 'reservas'));
    }

    public function adminHome()
    {
        $salas = $this->salaService->getSalasWhereIsActive();
        $reservas = $this->reservaService->getReservasFuturas();

        return view('home', compact('salas', 'reservas'));
    }

    public function userHome()
    {
        $salas = $this->salaService->getSalasWhereIsActive();
        $reservas = $this->reservaService->getReservasFuturas();

        return view('home.user', compact('salas', 'reservas'));
    }
}