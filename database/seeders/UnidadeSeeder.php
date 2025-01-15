<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unidade;

class UnidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           Unidade::factory()->create([
               'nome' => 'Gerência de Informática',
               'sigla' => 'GEINF'
           ]);
    }
}
