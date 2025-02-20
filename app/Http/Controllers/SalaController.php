<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sala;
use App\Models\User;
use App\Models\Reserva;


class SalaController extends Controller
{
    
     
     // Método para listar todas as salas
   

     public function index()
     {
         $salas = Sala::all();
         $reservas = Reserva::with('sala', 'user')->get(); // Carregue as relações sala e user
         return view('salas.index', compact('salas', 'reservas'));
     }
     

     //Método para exibir o formulário de criação de sala
    public function create()
    {
        $salas = Sala::all();
        $users = User::all();
        return view('reservas.create', compact('salas', 'users'));
    }

    // Método para armazenar uma nova sala
    public function store(Request $request)
{
    // Validação dos dados da requisição
    $request->validate([ 
        'nome' => 'required|string|max:255', 
        'descricao' => 'required|string|max:255', 
        'situacao' => 'required|in:ativa,inativa',
        'imagem' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validação da imagem
    ]);

    // Tratamento da imagem
    if ($request->hasFile('imagem')) { 
        $imagem = $request->file('imagem'); 
        $imageName = time().'.'.$imagem->getClientOriginalExtension(); 
        $imagem->move(public_path('img/salas'), $imageName); 
    } else { 
        $imageName = null; // Caso contrário, o valor será null
    }

    // Criação da nova sala 
    $sala = new Sala; 
    $sala->nome = $request->input('nome'); 
    $sala->descricao = $request->input('descricao'); 
    $sala->situacao = $request->input('situacao'); 
    $sala->imagem = $imageName; 
    $sala->save();

    // Redirecionamento após criação da sala 
    return redirect()->route('salas')->with('success', 'Sala criada com sucesso!');
}


   
    // Método para exibir uma sala específica
    public function show(Sala $sala)
    {
        return view('salas.show', compact('sala'));
    }

    
    // Método para exibir o formulário de edição de sala
    public function edit(Sala $sala)
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


         // Atualiza a sala
    $sala->update([
        'nome' => $request->nome,
        'descricao' => $request->descricao,
        'situacao' => $request->situacao,
    ]);

    // Redireciona com mensagem de sucesso
    return redirect()->route('salas')->with('success', 'Sala atualizada com sucesso!');


        //  // Atualização da sala 
        //  $sala->update($request->all()); 

        //  // Redirecionamento após atualização da sala 
        //  return redirect()->route('salas');
        
    }

      // Método para excluir uma sala
    public function destroy(Sala $sala)
    {
        $sala->delete();
        return redirect()->route('salas');
    }
}
