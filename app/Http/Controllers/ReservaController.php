<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\User;
use App\Models\Sala;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class ReservaController extends Controller
{
    public function index()
{
    $users = User::all(); // Uso do modelo User
    $reservas = Reserva::with('sala')->get(); // Carrega as reservas com suas salas
    $salas = Sala::all(); // Carrega as salas para o formulário
    return view('reservas.index', compact('reservas', 'salas'));
}
     public function create()
     {
        $salas = Sala::all();
        $users = User::all();
        $reservas = Reserva::with('sala')->get(); // Carrega as reservas e suas relações com as salas
        return view('reservas.create', compact('salas','reservas', 'users'));

        
     }

     public function store(Request $request)
        {
            // $salaQuery = Sala::where('id', 12);
            // dd($salaQuery->toSql());
            //  dd($request->all());
            try {
            $request->validate([
            //    'user_id' => 'required|exists:users,id',
               'sala_fk' => 'required|exists:salas,id',
               'data_reserva' => 'required|date',
               'hora_inicio' => 'required|date_format:H:i',
               'hora_termino' => 'required|date_format:H:i|after:hora_inicio',

            ]);

        
             Reserva::create([
                    
                  'sala_fk' => $request->input('sala_fk'),
                  'data_inicio' => $request->input('data_reserva') . ' ' . $request->input('hora_inicio'),
                  'data_fim' => $request->input('data_reserva') . ' ' . $request->input('hora_termino'),
          ]);

            } catch (\Exception $th) {
                dd($th);
            }
            // Salva os dados no banco
          

         return redirect()->route('reservas.index')->with('success', 'Reserva criada com sucesso!');
            
            $reserva = Reserva::create($request->all());
            return redirect()->route('reservas.index');
        }
        public function show(Reserva $reserva)
        {

            return view('reservas.show', compact('reserva'));
        }
        public function edit(Reserva $reserva)
        {
            $salas = Sala::all();
            $users = User::all();
            return view('reservas.edit', compact('reserva','salas','users'));
        }

        public function update(Request $request, Reserva $reserva)
        {
            $request->validate([
                'user_id'=> 'required|exists:users,id',
                'sala_id'=> 'required|exists:salas,id',
                'data_reserva'=> 'required|date',
                ]);
                $reserva->update($request->all());
                return redirect()->route('reservas.index');
        }

         public function destroy(Reserva $reserva)
           {
            $reserva->delete();
            return redirect()->route('reservas.index');
           }

          // Método personalizado para visualizar uma reserva específica 
          public function view($id) 
          { 
            $reserva = Reserva::findOrFail($id); 
            return view('reservas.view', compact('reserva')); 
        } 
        
        // Método personalizado para cancelar uma reserva específica 
        public function cancel($id) 
        { 
            $reserva = Reserva::findOrFail($id); 
            $reserva->delete(); return redirect()->route('reservas.index')->with('status', 'Reserva cancelada com sucesso!'); 
        } 
    }

        


