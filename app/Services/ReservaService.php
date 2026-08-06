<?php

namespace App\Services;

use App\Models\Reserva;
use App\Models\Sala;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;

class ReservaService
{
    protected PdfService $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    /**
     * Obtém lista de reservas com filtros flexíveis.
     *
     * @param array $filters
     *   - onlyActive (bool): se true, filtra por data_inicio >= hoje, is_active=1 e data_fim > now.
     *   - applyPermission (bool): se true, aplica restrições de usuário/unidade.
     *   - salaId (int|null): filtra por sala específica.
     *   - data (string|null): filtra por data específica (formato Y-m-d).
     *   - order (string): 'asc' ou 'desc' para data_inicio.
     *   - userOnly (bool): se true, filtra apenas reservas do usuário logado (ignora unidade).
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getReservas(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $defaults = [
            'onlyActive' => false,
            'applyPermission' => true,
            'salaId' => null,
            'data' => null,
            'order' => 'asc',
            'userOnly' => false,
        ];
        $filters = array_merge($defaults, $filters);

        $user = Auth::user();
        $query = Reserva::with(['sala', 'unidade', 'user.unidade']);

        // Filtro de ativas (futuras ou em andamento)
        if ($filters['onlyActive']) {
            $query->whereDate('data_inicio', '>=', Carbon::today())
                  ->where('is_active', 1)
                  ->where('data_fim', '>', Carbon::now());
        }

        // Filtro por sala
        if ($filters['salaId']) {
            $query->where('sala_fk', $filters['salaId']);
        }

        // Filtro por data (data_inicio)
        if ($filters['data']) {
            $query->whereDate('data_inicio', $filters['data']);
        }

        // Permissões
        if ($filters['applyPermission'] && !$user->isAdmin()) {
            if ($filters['userOnly']) {
                $query->where('user_id', $user->id);
            } else {
                $query->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('unidade_fk', $user->unidade_fk);
                });
            }
        }

        return $query->orderBy('data_inicio', $filters['order'])->get();
    }

    // Métodos legados para compatibilidade (delegam para o novo método)
    public function getActiveReservas(bool $applyPermission = true)
    {
        return $this->getReservas([
            'onlyActive' => true,
            'applyPermission' => $applyPermission,
            'order' => 'asc',
        ]);
    }

    public function getAllReservas(bool $applyPermission = true)
    {
        return $this->getReservas([
            'onlyActive' => false,
            'applyPermission' => $applyPermission,
            'order' => 'desc',
        ]);
    }

    public function listarReunioes()
    {
        return $this->getReservas([
            'onlyActive' => false,
            'applyPermission' => true,
            'userOnly' => true,
            'order' => 'asc',
        ]);
    }

    public function getReservasPorSalaEData($salaId, $data)
    {
        return $this->getReservas([
            'salaId' => $salaId,
            'data' => $data,
            'applyPermission' => false, // geralmente usado para exibição pública
        ]);
    }

    public function getReservasPorData($data)
    {
        if (!$data) {
            return collect([]);
        }
        return $this->getReservas([
            'data' => $data,
            'applyPermission' => false,
        ]);
    }

    // Métodos de criação/atualização/delete mantidos, com melhorias internas

    public function criarReserva(array $dados)
    {
        $this->validarSalaAtiva($dados['sala_fk']);

        $dataInicio = $this->parseDataHora($dados['data_reserva'], $dados['hora_inicio']);
        $dataFim = $this->parseDataHora($dados['data_reserva'], $dados['hora_termino']);

        $this->validarHorario($dataInicio, $dataFim);
        // Apenas validamos a data de início (fim será >= inicio)
        $this->validarDataReserva($dataInicio);

        if ($this->existeConflito($dados['sala_fk'], $dataInicio, $dataFim)) {
            throw new Exception('A sala já está reservada neste horário.');
        }

        $user = Auth::user();
        $unidadeId = ($user->isAdmin()) ? ($dados['unidade_fk'] ?? $user->unidade_fk) : $user->unidade_fk;

        return Reserva::create([
            'sala_fk' => $dados['sala_fk'],
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'user_id' => $user->id,
            'unidade_fk' => $unidadeId,
            'finalidade' => $dados['finalidade'] ?? $dados['tipo_reserva'] ?? null, // compatibilidade
        ]);
    }

    public function atualizarReserva(Reserva $reserva, array $dados)
    {
        $user = Auth::user();

        if (!($user->isAdmin() || $user->id === $reserva->user_id)) {
            throw new Exception('Sem permissão para alterar esta reserva.');
        }

        $dataInicio = $this->parseDataHora($dados['data_reserva'], $dados['hora_inicio']);
        $dataFim = $this->parseDataHora($dados['data_reserva'], $dados['hora_termino']);

        $this->validarHorario($dataInicio, $dataFim);
        $this->validarDataReserva($dataInicio); // só valida início

        if ($this->existeConflito($dados['sala_fk'], $dataInicio, $dataFim, $reserva->id)) {
            throw new Exception('A sala já está reservada neste horário por outra pessoa.');
        }

        $unidadeId = $user->isAdmin() ? ($dados['unidade_fk'] ?? $reserva->unidade_fk) : $user->unidade_fk;

        return $reserva->update([
            'sala_fk' => $dados['sala_fk'],
            'data_inicio' => $dataInicio,
            'data_fim' => $dataFim,
            'unidade_fk' => $unidadeId,
            'finalidade' => $dados['finalidade'] ?? $dados['tipo_reserva'] ?? $reserva->finalidade,
        ]);
    }

    public function encerrarReserva(Reserva $reserva)
    {
        $user = Auth::user();

        if (!($user->isAdmin() || $user->id === $reserva->user_id)) {
            throw new Exception('Sem permissão para encerrar esta reserva.');
        }
        return $reserva->update([
            'is_active' => 0,
        ]);
    }

    public function deletarReserva(Reserva $reserva)
    {
        $user = Auth::user();

        if (!($user->isAdmin() || $user->id === $reserva->user_id)) {
            throw new Exception('Sem permissão para deletar esta reserva.');
        }

        return $reserva->delete();
    }

    // Método cancelarReserva removido – use deletarReserva diretamente
    // Se precisar manter para compatibilidade, delegue:
    public function cancelarReserva($id)
    {
        $reserva = Reserva::findOrFail($id);
        return $this->deletarReserva($reserva);
    }

    public function buscarReserva($id)
    {
        return Reserva::findOrFail($id);
    }

    public function getEventos()
    {
        $reservas = $this->getReservas([
            'onlyActive' => false, // pegamos todas, mas aplicamos o filtro de ativas internamente? 
            // A lógica original pegava todas com is_active=1, sem filtrar data_fim > now.
            // Mantemos o comportamento original para não quebrar:
        ]);
        // Mas a query original era: where('is_active', 1) sem filtro de data_fim.
        // Para manter exatamente igual, faremos uma consulta separada:
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

    /**
     * Gera o PDF com as reservas de um mês específico, respeitando permissões.
     */
    public function getPdfReservasPorMes(?int $mes = null, ?int $ano = null)
    {
        $mes = $mes ?? Carbon::now()->month;
        $ano = $ano ?? Carbon::now()->year;

        // Usamos o método getReservas com permissão aplicada por padrão
        $reservas = $this->getReservas([
            'onlyActive' => false,
            'applyPermission' => true,
            'order' => 'asc',
        ])->filter(function ($reserva) use ($mes, $ano) {
            return Carbon::parse($reserva->data_inicio)->month == $mes
                && Carbon::parse($reserva->data_inicio)->year == $ano;
        });

        $nomeMes = Carbon::createFromDate($ano, $mes, 1)->translatedFormat('F/Y');

        $dados = [
            'reservas' => $reservas,
            'periodo' => $nomeMes
        ];

        return $this->pdfService->gerarDeView('reservas.relatorio-reservas', $dados, 'a4', 'landscape');
    }

    // ---------- Métodos auxiliares privados ----------

    private function parseDataHora(string $data, string $hora): Carbon
    {
        return Carbon::parse($data . ' ' . $hora);
    }

    private function hexToRgba(string $hex, float $opacity = 1.0): string
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

    private function existeConflito($salaId, Carbon $inicio, Carbon $fim, $idIgnorar = null): bool
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

    private function validarSalaAtiva($salaId): void
    {
        $sala = Sala::findOrFail($salaId);
        if (strtolower(trim($sala->situacao)) !== 'ativa') {
            throw new Exception('A sala está em manutenção e não pode ser reservada.');
        }
    }

    private function validarHorario(Carbon $inicio, Carbon $fim): void
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

    private function validarDataReserva(Carbon $data): void
    {
        if ($data->isWeekend()) {
            throw new Exception('Não é possível marcar uma reserva durante o fim de semana.');
        }

        if ($data->copy()->startOfDay()->lt(Carbon::today())) {
            throw new Exception('Não é possível marcar uma reserva em uma data retroativa.');
        }
    }
}