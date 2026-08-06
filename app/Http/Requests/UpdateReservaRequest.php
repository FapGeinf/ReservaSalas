<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateReservaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sala_fk'      => 'required|exists:salas,id',
            'data_reserva' => 'required|date|after_or_equal:today',
            'hora_inicio'  => 'required|date_format:H:i',
            'hora_termino' => 'required|date_format:H:i|after:hora_inicio',
            'tipo_reserva' => 'nullable|string|max:255',
            'unidade_fk'   => [
                Auth::user()->isAdmin ? 'required' : 'nullable',
                'exists:unidades,id'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'sala_fk.required'           => 'Selecione uma sala.',
            'sala_fk.exists'             => 'A sala selecionada é inválida.',
            'data_reserva.required'      => 'A data da reserva é obrigatória.',
            'data_reserva.date'          => 'Informe uma data válida.',
            'data_reserva.after_or_equal' => 'A data deve ser hoje ou uma data futura.',
            'hora_inicio.required'       => 'A hora de início é obrigatória.',
            'hora_inicio.date_format'    => 'Formato de hora inválido (HH:mm).',
            'hora_termino.required'      => 'A hora de término é obrigatória.',
            'hora_termino.after'         => 'A hora de término deve ser maior que a hora de início.',
            'unidade_fk.required'        => 'Como administrador, você deve informar a unidade.',
            'unidade_fk.exists'          => 'A unidade selecionada não existe.',
        ];
    }
}