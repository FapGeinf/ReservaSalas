<?php

namespace App\Services;

use App\Models\Reserva;
use App\Models\Sala;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;

class ReservaService
{
    public function getActiveReservas(bool $applyPermission = true)
    {
        $user = Auth::user();
        $today = Carbon::today();

        $query = Reserva::with(['sala', 'unidade', 'user.unidade'])
            ->whereDate('data_inicio', '>=', $today)
            ->where('is_active', 1);

        if ($applyPermission && !$user->is_admin) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('unidade_fk', $user->unidade_fk);
            });
        }

        return $query->orderBy('data_inicio', 'asc')->get();
    }


    public function getAllReservas(bool $applyPermission = true)
    {
        $user = Auth::user();

        $query = Reserva::with(['sala', 'unidade', 'user.unidade']);

        if ($applyPermission && !$user->is_admin) {
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('unidade_fk', $user->unidade_fk);
            });
        }

        return $query->orderBy('data_inicio', 'desc')->get();
    }

    public function criarReserva(array $dados)
    {
        $this->validarSalaAtiva($dados['sala_fk']);

        $dataInicio = Carbon::parse($dados['data_reserva'] . ' ' . $dados['hora_inicio']);
        $dataFim = Carbon::parse($dados['data_reserva'] . ' ' . $dados['hora_termino']);

        $this->validarHorario($dataInicio, $dataFim);
        $this->validarDataReserva($dataInicio);
        $this->validarDataReserva($dataFim);

        if ($this->existeConflito($dados['sala_fk'], $dataInicio, $dataFim)) {
            throw new Exception('A sala já está reservada neste horário.');
        }

        $user = Auth::user();
        $unidadeId = ($user->is_admin == 1) ? ($dados['unidade_fk'] ?? $user->unidade_fk) : $user->unidade_fk;

        return Reserva::create([
            'sala_fk' => $dados['sala_fk'],
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'user_id' => $user->id,
            'unidade_fk' => $unidadeId,
            'finalidade' => $dados['tipo_reserva'],
        ]);
    }

    public function atualizarReserva(Reserva $reserva, array $dados)
    {
        $user = Auth::user();

        if (!($user->is_admin || $user->id === $reserva->user_id)) {
            throw new Exception('Sem permissão para alterar esta reserva.');
        }

        $dataInicio = Carbon::parse($dados['data_reserva'] . ' ' . $dados['hora_inicio']);
        $dataFim = Carbon::parse($dados['data_reserva'] . ' ' . $dados['hora_termino']);

        $this->validarHorario($dataInicio, $dataFim);
        $this->validarDataReserva($dataInicio);
        $this->validarDataReserva($dataFim);

        if ($this->existeConflito($dados['sala_fk'], $dataInicio, $dataFim, $reserva->id)) {
            throw new Exception('A sala já está reservada neste horário por outra pessoa.');
        }

        $unidadeId = $user->is_admin ? ($dados['unidade_fk'] ?? $reserva->unidade_fk) : $user->unidade_fk;

        return $reserva->update([
            'sala_fk' => $dados['sala_fk'],
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'unidade_fk' => $unidadeId,
            'finalidade' => $dados['tipo_reserva'] ?? $reserva->finalidade,
        ]);
    }

    public function encerrarReserva(Reserva $reserva)
    {
        $user = Auth::user();

        if (!($user->is_admin || $user->id === $reserva->user_id)) {
            throw new Exception('Sem permissão para encerrar esta reserva.');
        }
        return $reserva->update([
            'is_active' => 0,
        ]);
    }

    public function deletarReserva(Reserva $reserva)
    {
        $user = Auth::user();

        if (!($user->is_admin || $user->id === $reserva->user_id)) {
            throw new Exception('Sem permissão para deletar esta reserva.');
        }

        return $reserva->delete();
    }

    public function buscarReserva($id)
    {
        return Reserva::findOrFail($id);
    }

    public function cancelarReserva($id)
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->delete();

        return true;
    }

    public function getReservasPorSalaEData($salaId, $data)
    {
        return Reserva::where('sala_fk', $salaId)
            ->whereDate('data_inicio', $data)
            ->with(['user', 'user.unidade'])
            ->get();
    }

    public function getReservasPorData($data)
    {
        if (!$data) {
            return [];
        }

        return Reserva::with(['sala', 'user.unidade'])
            ->whereDate('data_inicio', $data)
            ->orderBy('data_inicio', 'asc')
            ->get();
    }

    public function getEventos()
    {
        $reservas = Reserva::with(['sala', 'unidade', 'user.unidade'])
            ->where('is_active', 1)
            ->get();
        $now = Carbon::now();

        $events = [];

        foreach ($reservas as $reserva) {

            $isPast = Carbon::parse($reserva->data_fim)->lt($now);

            $color = $reserva->sala->cor ?? '#3788d8';

            $backgroundColor = $isPast ? $this->hexToRgba($color, 0.90) : $color;
            $borderColor = $isPast ? $this->hexToRgba($color, 0.90) : $color;
            $textColor = $isPast ? '#333333' : '#ffffff';

            $events[] = [
                'id' => $reserva->id,
                'title' => $reserva->sala?->nome,
                'start' => Carbon::parse($reserva->data_inicio)->format('Y-m-d\TH:i:s'),
                'end' => Carbon::parse($reserva->data_fim)->format('Y-m-d\TH:i:s'),
                'backgroundColor' => $backgroundColor,
                'borderColor' => $borderColor,
                'textColor' => $textColor,
                'extendedProps' => [
                    'unidade' => $reserva->unidade->sigla ?? '',
                    'hora_inicio' => Carbon::parse($reserva->data_inicio)->format('H:i'),
                    'hora_fim' => Carbon::parse($reserva->data_fim)->format('H:i'),
                    'data_inicio' => Carbon::parse($reserva->data_inicio)->format('Y-m-d'),
                    'sala_fk' => $reserva->sala_fk,
                    'unidade_fk' => $reserva->unidade_fk,
                    'responsavel' => $reserva->user->name,
                    'finalidade' => $reserva->finalidade
                ]
            ];
        }

        return $events;
    }

    public function listarReunioes()
    {
        return Reserva::with('sala', 'user.unidade')->get();
    }

    private function hexToRgba($hex, $opacity = 1.0)
    {
        $hex = str_replace('#', '', $hex);

        if (strlen($hex) === 3) {
            $r = hexdec(str_repeat(substr($hex, 0, 1), 2));
            $g = hexdec(str_repeat(substr($hex, 1, 1), 2));
            $b = hexdec(str_repeat(substr($hex, 2, 1), 2));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }

        return "rgba($r, $g, $b, $opacity)";
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

        if ($inicio->isPast()) {
            if (!$inicio->isToday() || $inicio->lt(Carbon::now()->subMinutes(5))) {
                throw new Exception('Não é possível realizar ou editar reservas para horários que já passaram.');
            }
        }
    }

    private function validarDataReserva($data_reserva)
    {
        if (!$data_reserva instanceof Carbon) {
            $data_reserva = Carbon::parse($data_reserva);
        }

        if ($data_reserva->isWeekend()) {
            throw new Exception('Não é possível marcar uma reserva durante o fim de semana.');
        }

        if ($data_reserva->copy()->startOfDay()->lt(Carbon::today())) {
            throw new Exception('Não é possível marcar uma reserva em uma data retroativa.');
        }
    }
}