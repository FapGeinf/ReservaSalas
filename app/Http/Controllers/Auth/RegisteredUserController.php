<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Unidade;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $unidades = Unidade::all();
        $usuarios = User::all(); // Carregar todos os usuários

        return view('auth.register', compact('unidades', 'usuarios'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'cpf' => 'required|string|max:14|unique:users', // Validação do CPF
            'unidade_fk' => 'required|exists:unidades,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf'=> $request->cpf,
            'unidade_fk' => $request->unidade_fk
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard'));
    }

//     public function edit($id): View
// {
//     $usuario = User::findOrFail($id); // Busca o usuário pelo ID
//     $unidades = Unidade::all(); // Busca todas as unidades para o dropdown
//     return view('auth.edit', compact('usuario', 'unidades'));
// }

public function edit($id): View
{
    $usuario = User::find($id); // Busca o usuário pelo ID

    // Verifica se o usuário existe
    if (!$usuario) {
        return redirect()->route('usuarios.index')->with('error', 'Usuário não encontrado.');
    }

    $unidades = Unidade::all(); // Busca todas as unidades para o dropdown
    return view('profile.edit', compact('usuario', 'unidades'));
}


public function update(Request $request, $id): RedirectResponse
{
    // Validação dos dados
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        'cpf' => 'required|string|max:14|unique:users,cpf,' . $id,
        'unidade_fk' => 'required|exists:unidades,id',
    ]);

    // Atualiza o usuário
    $usuario = User::findOrFail($id);
    $usuario->update([
        'name' => $request->name,
        'email' => $request->email,
        'cpf' => $request->cpf,
        'unidade_fk' => $request->unidade_fk,
    ]);

    return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
}

public function destroy($id): RedirectResponse
{
    $usuario = User::findOrFail($id);
    $usuario->delete();

    return redirect()->route('usuarios.index')->with('success', 'Usuário excluído com sucesso!');
}


}