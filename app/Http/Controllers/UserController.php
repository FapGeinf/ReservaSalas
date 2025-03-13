<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Unidade;
use App\Models\User;



class UserController extends Controller
{
    

    public function create()
{
    $unidades = Unidade::all(); // Busca todas as unidades para o dropdown
    return view('usuarios.create', compact('unidades'));
}

    public function store(Request $request)
{
    // Validação dos dados
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'cpf' => 'required|string|max:14|unique:users',
        'unidade_fk' => 'required|exists:unidades,id',
        'password' => 'required|string|min:8|confirmed',
    ]);

    // Cria o usuário
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'cpf' => $request->cpf,
        'unidade_fk' => $request->unidade_fk,
        'password' => bcrypt($request->password),
    ]);

    return redirect()->route('usuarios.index')->with('success', 'Usuário cadastrado com sucesso!');
}
    public function update(Request $request, $id)
{
    $request->validate([
        'cpf' => 'required|unique:users,cpf,' . $id, // Permite atualizar sem duplicar
    ]);

    $user = User::findOrFail($id);
    $user->cpf = $request->cpf;
    $user->save();

    return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
}



}
