<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:255',
            'username'   => 'nullable|string|max:255',
            'login'      => 'required|string|max:255|unique:users,login',
            'unidade_fk' => 'required|exists:unidades,id',
            'password'   => 'required|string|min:8|confirmed',
            'cpf'        => 'nullable|string|max:14',
            'nivel_acesso_id' => 'required|integer|exists:nivel_acessos,id',
        ];
    }

    public function messages(): array
    {
        return [
            'login.unique' => 'Este login já está em uso.',
            'password.confirmed' => 'As senhas não conferem.',
            'unidade_fk.exists' => 'A unidade selecionada é inválida.',
        ];
    }
}