<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório Mensal de Reservas</title>
    <style>
        @page {
            margin: 35px 25px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }

        /* Cabeçalho */
        .header {
            width: 100%;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header table {
            width: 100%;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
        }

        .subtitle {
            font-size: 13px;
            color: #64748b;
            margin-top: 5px;
        }

        .date-generated {
            text-align: right;
            font-size: 11px;
            color: #64748b;
        }

        /* Tabela de Dados */
        .table-reservas {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table-reservas th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            padding: 8px;
            text-align: left;
        }

        .table-reservas td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        .table-reservas tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Status Badges */
        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }

        .badge-confirmada {
            background-color: #dcfce7;
            color: #15803d;
        }

        .badge-pendente {
            background-color: #fef9c3;
            color: #a16207;
        }

        .badge-cancelada {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        /* Rodapé com paginação automática */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 20px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }

        .page-number:before {
            content: counter(page);
        }
    </style>
</head>

<body>

    <!-- Cabeçalho do Relatório -->
    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="title">Relatório Mensal de Reservas</div>
                    <div class="subtitle">Período: {{ ucfirst($periodo) }}</div>
                </td>
                <td class="date-generated">
                    Gerado em: {{ now()->format('d/m/Y H:i') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Tabela de Reservas -->
    <table class="table-reservas">
        <thead>
            <tr>
                <th style="width: 10%;">ID</th>
                <th style="width: 35%;">Responsável / Evento</th>
                <th style="width: 20%;">Sala / Espaço</th>
                <th style="width: 15%;">Data Início</th>
                <th style="width: 10%;">Horário</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservas as $reserva)
                <tr>
                    <td>#{{ $reserva->id }}</td>
                    <td>
                        <!-- Relacionamento com User -->
                        <strong>{{ $reserva->user->name ?? 'N/A' }}</strong><br>
                        <!-- Campo finalidade (interno ou pesquisador) -->
                        <span style="font-size: 10px; color: #64748b;">
                            Tipo: {{ ucfirst($reserva->finalidade ?? 'Não informado') }}
                        </span>
                    </td>
                    <td>
                        <!-- Relacionamento com Sala e Unidade -->
                        <strong>{{ $reserva->sala->nome ?? 'Sala não informada' }}</strong><br>
                        <span style="font-size: 10px; color: #64748b;">
                            Bloco/Unid: {{ $reserva->unidade->nome ?? 'N/A' }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y') }}</td>
                    <td>
                        <!-- Exibe o intervalo exato de horas da reserva -->
                        {{ \Carbon\Carbon::parse($reserva->data_inicio)->format('H:i') }} às
                        {{ \Carbon\Carbon::parse($reserva->data_fim)->format('H:i') }}
                    </td>
                    <td>
                        <!-- Status baseado no campo is_active -->
                        @if($reserva->is_active)
                            <span class="badge badge-confirmada">Ativa</span>
                        @else
                            <span class="badge badge-cancelada">Inativa</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px; color: #64748b;">
                        Nenhuma reserva encontrada para este período.
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>

    <div class="footer">
        Relatório de Reservas | Página <span class="page-number"></span>
    </div>

</body>

</html>