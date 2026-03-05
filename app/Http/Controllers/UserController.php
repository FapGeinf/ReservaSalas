<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Unidade;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {

        $usuarios = User::with('unidade')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $unidades = Unidade::all();
        return view('usuarios.create', compact('unidades'));
    }

    public function store(StoreUserRequest $request)
    {
        $result = $this->userService->createUser($request->validated());

        if (!$result['success']) {
            return redirect()->back()->withInput()->with('error', $result['message']);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuário cadastrado!');
    }

    
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $unidades = Unidade::all();

        return response()->json([
            'user' => $user,
            'unidades' => $unidades
        ]);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $result = $this->userService->updateUser($id, $request->validated());

        if (!$result['success']) {
            return redirect()->back()->withInput()->with('error', $result['message']);
        }

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
    }

   
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('usuarios.index')->with('success', 'Usuário removido com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('usuarios.index')->with('error', 'Erro ao excluir usuário.');
        }
    }

    public function marcarTutorial(Request $request)
    {
        $this->userService->updateTutorialStatus(auth()->user());
        return response()->json(['status' => 'ok']);
    }
}