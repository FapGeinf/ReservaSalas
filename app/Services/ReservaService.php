<?php

namespace App\Services;
use App\Models\Reserva;

class ReservaService
{
      public function getReservas()
      {
             return Reserva::with('sala', 'user')->get();
      }
}