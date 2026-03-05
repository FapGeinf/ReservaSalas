<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreReservaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'sala_fk' => 'required|exists:salas,id',
            'data_reserva' => [
                'required',
                'date',
                'after_or_equal:today', 
            ],
            'hora_inicio' => 'required|date_format:H:i',
            'hora_termino' => 'required|date_format:H:i|after:hora_inicio',
            'unidade_fk' => 'required_if:is_admin,1', 
            'tipo_reserva' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'sala_fk.required' => 'Selecione uma sala.',
            'sala_fk.exists' => 'Sala não encontrada.',
            'data_reserva.required' => 'Informe a data da reserva.',
            'data_reserva.after_or_equal' => 'A data escolhida deve ser hoje ou uma futura.',
            'hora_inicio.required' => 'Informe a hora de início.',
            'hora_termino.after' => 'A hora de término deve ser após a hora de início.',
        ];
    }
}