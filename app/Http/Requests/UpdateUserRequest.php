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
        // Pega o parâmetro 'id' ou 'user' da rota explicitamente
        $userId = $this->route('user') ?? $this->route('id');

        return [
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255',
            'login' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'login')->ignore($userId),
            ],
            'unidade_fk' => 'required|exists:unidades,id',
            'is_admin' => 'nullable|boolean',
            'nivel_acesso_id' => 'required|integer|exists:niveis_acessos,id',
            'password' => 'nullable|string|min:8|confirmed',
            'cpf' => 'nullable|string|max:14',
        ];
    }

    public function messages(): array
    {
        return [
            'login.unique' => 'Este login já está sendo usado por outro usuário.',
            'login.required' => 'O campo login é obrigatório.',
            'password.min' => 'A nova senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'As senhas não conferem.',
            'unidade_fk.required' => 'Selecione uma unidade válida.',
            'unidade_fk.exists' => 'A unidade selecionada é inválida.',
        ];
    }
}