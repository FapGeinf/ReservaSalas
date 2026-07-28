<?php

namespace App\Services;
use App\Models\Unidade;

class UnidadeService
{
      public function getUnidades()
      {
             return Unidade::orderBy('nome')->get();    
      }
}