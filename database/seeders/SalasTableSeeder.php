<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Salas;

class SalasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('salas')->insert([
            [
                'nome' => 'Aquário',
                'descricao' => 'Tela de projeção',
                'imagem' => 'sala1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'cor' => '#FF5733', // Vermelho
                'situacao' => 'ativa',
            ],

            [
                'nome' => 'Daf/Ditec',
                'descricao' => 'TV, Datashow',
                'imagem' => 'sala2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'cor' => '#33FF57', // Verde
                'situacao' => 'ativa',
            ],

            [
                'nome' => 'Presidência',
                'descricao' => 'Tela de projeção, Datashow',
                'imagem' => 'sala3.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'cor' => '#3357FF', // Azul
                'situacao' => 'ativa',
            ],

            [
                'nome' => 'Auditório Tauató',
                'descricao' => 'Tela de projeção, Datashow',
                'imagem' => 'sala4.png',
                'created_at' => now(),
                'updated_at' => now(),
                'cor' => '#FF33A1', // Rosa
                'situacao' => 'ativa',
            ],
           
        ]);
    }
}