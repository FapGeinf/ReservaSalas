<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
			'descricao' => 'required|string|max:255',
			'situacao' => 'required|in:ativa,inativa',
			'imagem' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
			'cor' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'O campo nome é obrigatório.',
            'descricao.required' => 'O campo descrição é obrigatório.',
            'situacao.required' => 'O campo situação é obrigatório.',
            'situacao.in' => 'O campo situação deve ser "ativa" ou "inativa".',
            'imagem.image' => 'O arquivo deve ser uma imagem.',
            'imagem.mimes' => 'A imagem deve ser do tipo jpg, jpeg ou png.',
            'imagem.max' => 'A imagem não pode ser maior que 2MB.',
        ];
    }
}
