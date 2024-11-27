<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sala;

class SalaController extends Controller
{
    
     
     // Método para listar todas as salas
    public function index()
    {
        $salas = Sala::all();
        return view('salas.index', compact('salas'));
    }

     //Método para exibir o formulário de criação de sala
    public function create()
    {
        return view('salas.create');
    }

    // Método para armazenar uma nova sala
    public function store(Request $request)
    {
        // Validação dos dados da requisição
        $request->validate([ 
            'nome' => 'required|string|max:255', 
            'descricao' => 'required|string|max:255', 
            'situacao' => 'required|in:ativa,inativa',
    ]);
        // Criação da nova sala 
        Sala::create($request->all()); 

        // Redirecionamento após criação da sala 
        return redirect()->route('salas.index');

    }

   
    // Método para exibir uma sala específica
    public function show(Sala $sala)
    {
        return view('salas.show', compact('sala'));
    }

    
    // Método para exibir o formulário de edição de sala
    public function edit(string $id)
    {
        return view('salas.edit', compact('sala'));
    }

    // Método para atualizar uma sala existente
    public function update(Request $request, Sala $sala)
    {
        // Validação dos dados da requisição 
        $request->validate([ 
            'nome' => 'required|string|max:255', 
            'descricao' => 'required|string|max:255', 
            'situacao' => 'required|in:ativa,inativa',
         ]);

         // Atualização da sala 
         $sala->update($request->all()); 

         // Redirecionamento após atualização da sala 
         return redirect()->route('salas.index');
    }

      // Método para excluir uma sala
    public function destroy(Sala $sala)
    {
        $sala->delete();
        return redirect()->route('salas.index');
    }
}
