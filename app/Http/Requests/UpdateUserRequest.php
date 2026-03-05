<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        $userId = $this->route('id'); 

        return [
            'name'       => 'required|string|max:255',
            'login'      => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'login')->ignore($userId),
            ],
            'unidade_fk' => 'required|exists:unidades,id',
            'is_admin'   => 'required|boolean', 
            'password'   => 'nullable|string|min:8', 
        ];
    }

    public function messages(): array
    {
        return [
            'login.unique' => 'Este login já está sendo usado por outro usuário.',
            'login.required' => 'O campo login é obrigatório.',
            'password.min' => 'A nova senha deve ter pelo menos 8 caracteres.',
            'unidade_fk.required' => 'Selecione uma unidade válida.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'is_admin' => $this->has('is_admin') ? true : false,
        ]);
    }
}