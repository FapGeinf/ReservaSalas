<?php

namespace App\Services;

use App\Models\Reserva;
use App\Models\Sala;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            Log::warning("Tentativa de criar reserva conflitante: sala {$dados['sala_fk']} de {$dataInicio} a {$dataFim}");
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
            Log::warning("Tentativa de atualizar reserva conflitante: sala {$dados['sala_fk']} de {$dataInicio} a {$dataFim}, ignorando ID {$reserva->id}");
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

    public function encerrarReserva(Reserva $reserva)
    {
        $user = Auth::user();
        $agora = Carbon::now();
        $inicio = Carbon::parse($reserva->data_inicio);

        if (!($user->is_admin || $user->id === $reserva->user_id)) {
            throw new Exception('Sem permissão para encerrar esta reserva.');
        }

        if (Carbon::parse($reserva->data_fim)->isPast()) {
            throw new Exception('Esta reserva já foi finalizada ou o horário já expirou.');
        }

        if ($agora->lt($inicio)) {
            throw new Exception('Não é possível encerrar uma reserva que ainda não começou.');
        }

        if ($agora->equalTo($inicio)) {
            $agora->addMinute();
        }

        return $reserva->update([
            'data_fim' => $agora
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
        $reservas = Reserva::with(['sala', 'user.unidade'])->get();
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
                    'unidade' => $reserva->user->unidade->sigla ?? '',
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

    /**
     * Converte cor hexadecimal para RGBA com opacidade.
     */
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

    /**
     * Verifica se há conflito de horário na mesma sala.
     * Usa < e > para que reservas que terminam exatamente no início de outra não conflitem.
     */
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

        $existe = $query->exists();

        if ($existe) {
            Log::debug("Conflito encontrado na sala {$salaId} para o período {$inicio} - {$fim}");
        }

        return $existe;
    }

    /**
     * Valida se a sala está com situação 'ativa'.
     */
    private function validarSalaAtiva($salaId)
    {
        $sala = Sala::findOrFail($salaId);
        if (strtolower(trim($sala->situacao)) !== 'ativa') {
            throw new Exception('A sala está em manutenção e não pode ser reservada.');
        }
    }

    /**
     * Valida se o horário é válido (início < fim) e se não está no passado.
     * Permite reservas para hoje desde que o início seja >= agora (com 5 min de tolerância).
     */
    private function validarHorario(Carbon $inicio, Carbon $fim)
    {
        if ($fim->lte($inicio)) {
            throw new Exception("O horário de término ({$fim->format('H:i')}) deve ser após o início ({$inicio->format('H:i')}).");
        }

        $agora = Carbon::now();

        if ($inicio->lt($agora->copy()->startOfDay())) {
            throw new Exception("Não é possível reservar para uma data anterior a hoje ({$inicio->format('d/m/Y')}).");
        }

        if ($inicio->isToday() && $inicio->lt($agora->copy()->subMinutes(5))) {
            throw new Exception("Não é possível reservar para um horário que já passou. O início ({$inicio->format('H:i')}) é anterior a " . $agora->copy()->addMinutes(5)->format('H:i') . ".");
        }
    }
}