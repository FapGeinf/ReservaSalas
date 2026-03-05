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
            'unidade_fk' => 'required|exists:unidades,id',
            'login'      => 'required|string|max:255|unique:users,login',
            'password'   => 'required|string|min:8|confirmed',
            'role'       => 'required|in:user,admin',
            'cpf'        => 'nullable|string|max:14',
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