<?php

namespace App\Services;

use App\Models\Reserva;
use App\Models\Sala;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;

class ReservaService
{
    public function getReservas()
    {
        return Reserva::with('sala', 'user.unidade')->get();
    }

    public function getReservasOrderByData()
    {
        return Reserva::with('sala', 'user.unidade')
            ->orderBy('data_inicio', 'desc') 
            ->get();
    }

    public function criarReserva(array $dados)
    {
        $this->validarSalaAtiva($dados['sala_fk']);

        $dataInicio = Carbon::parse($dados['data_reserva'] . ' ' . $dados['hora_inicio']);
        $dataFim    = Carbon::parse($dados['data_reserva'] . ' ' . $dados['hora_termino']);

       
        $this->validarHorario($dataInicio, $dataFim);

        if ($this->existeConflito($dados['sala_fk'], $dataInicio, $dataFim)) {
            throw new Exception('A sala já está reservada neste horário.');
        }
        
        $user = Auth::user();
      
        $unidadeId = ($user->is_admin == 1) ? ($dados['unidade_fk'] ?? $user->unidade_fk) : $user->unidade_fk;

        return Reserva::create([
            'sala_fk'     => $dados['sala_fk'],
            'data_inicio' => $dataInicio,
            'data_fim'    => $dataFim,
            'user_id'     => $user->id,
            'unidade_fk'  => $unidadeId,
            'finalidade'  => $dados['tipo_reserva'],
        ]);
    }

    public function atualizarReserva(Reserva $reserva, array $dados)
    {
        $user = Auth::user();

        if (!($user->is_admin || $user->id === $reserva->user_id)) {
            throw new Exception('Sem permissão para alterar esta reserva.');
        }

        $dataInicio = Carbon::parse($dados['data_reserva'] . ' ' . $dados['hora_inicio']);
        $dataFim    = Carbon::parse($dados['data_reserva'] . ' ' . $dados['hora_termino']);

        $this->validarHorario($dataInicio, $dataFim);

        if ($this->existeConflito($dados['sala_fk'], $dataInicio, $dataFim, $reserva->id)) {
            throw new Exception('A sala já está reservada neste horário por outra pessoa.');
        }

        $unidadeId = $user->is_admin ? ($dados['unidade_fk'] ?? $reserva->unidade_fk) : $user->unidade_fk;

        return $reserva->update([
            'sala_fk'     => $dados['sala_fk'],
            'data_inicio' => $dataInicio,
            'data_fim'    => $dataFim,
            'unidade_fk'  => $unidadeId,
            'finalidade'  => $dados['tipo_reserva'] ?? $reserva->finalidade,
        ]);
    }

    private function existeConflito($salaId, $inicio, $fim, $idIgnorar = null)
    {
        $query = Reserva::where('sala_fk', $salaId)
            ->where(function ($q) use ($inicio, $fim) {
                $q->where('data_inicio', '<', $fim)
                  ->where('data_fim', '>', $inicio);
            });

        if ($idIgnorar) {
            $query->where('id', '!=', $idIgnorar);
        }

        return $query->exists();
    }

    private function validarSalaAtiva($salaId)
    {
        $sala = Sala::findOrFail($salaId);
        if (strtolower(trim($sala->situacao)) !== 'ativa') {
            throw new Exception('A sala está em manutenção e não pode ser reservada.');
        }
    }

    private function validarHorario(Carbon $inicio, Carbon $fim)
    {
        if ($fim->lte($inicio)) {
            throw new Exception('A hora de término deve ser após a hora de início.');
        }

        if ($inicio->isPast() && !$inicio->isToday()) {
            throw new Exception('Não é possível realizar ou editar reservas para datas passadas.');
        }
    }
}