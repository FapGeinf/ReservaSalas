<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Unidade;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        try {
            $usuarios = User::with('unidade')->get();
            return view('usuarios.index', compact('usuarios'));
        } catch (\Exception $e) {
            Log::error('Erro ao listar usuários: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with('error', 'Erro ao carregar a lista de usuários.');
        }
    }

    public function create()
    {
        try {
            $unidades = Unidade::all();
            return view('usuarios.create', compact('unidades'));
        } catch (\Exception $e) {
            Log::error('Erro ao carregar tela de cadastro de usuário: ' . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->route('usuarios.index')->with('error', 'Erro ao carregar o formulário de cadastro.');
        }
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $result = $this->userService->createUser($request->validated());

            if (!$result['success']) {
                Log::warning('Falha ao cadastrar usuário: ' . ($result['message'] ?? 'Motivo desconhecido'), [
                    'data' => $request->except(['password', 'password_confirmation'])
                ]);
                return redirect()->back()->withInput()->with('error', $result['message']);
            }

            return redirect()->route('usuarios.index')->with('success', 'Usuário cadastrado!');
        } catch (\Exception $e) {
            Log::error('Erro inesperado ao cadastrar usuário: ' . $e->getMessage(), [
                'exception' => $e,
                'data' => $request->except(['password', 'password_confirmation'])
            ]);
            return redirect()->back()->withInput()->with('error', 'Ocorreu um erro inesperado ao cadastrar o usuário.');
        }
    }

    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            $unidades = Unidade::all();

            return response()->json([
                'user' => $user,
                'unidades' => $unidades
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar dados para edição do usuário ID ' . $id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $id
            ]);
            return response()->json([
                'error' => 'Não foi possível carregar os dados do usuário.'
            ], 500);
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $result = $this->userService->updateUser($id, $request->validated());

            if (!$result['success']) {
                Log::warning('Falha ao atualizar usuário ID ' . $id . ': ' . ($result['message'] ?? 'Motivo desconhecido'), [
                    'user_id' => $id,
                    'data' => $request->except(['password', 'password_confirmation'])
                ]);
                return redirect()->back()->withInput()->with('error', $result['message']);
            }

            return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro inesperado ao atualizar usuário ID ' . $id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $id,
                'data' => $request->except(['password', 'password_confirmation'])
            ]);
            return redirect()->back()->withInput()->with('error', 'Ocorreu um erro inesperado ao atualizar o usuário.');
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return redirect()->route('usuarios.index')->with('success', 'Usuário removido com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir usuário ID ' . $id . ': ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $id
            ]);
            return redirect()->route('usuarios.index')->with('error', 'Erro ao excluir usuário.');
        }
    }

    public function marcarTutorial(Request $request)
    {
        try {
            $this->userService->updateTutorialStatus(auth()->user());
            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('Erro ao marcar status do tutorial para o usuário ID ' . auth()->id() . ': ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => auth()->id()
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao atualizar status do tutorial.'
            ], 500);
        }
    }
}