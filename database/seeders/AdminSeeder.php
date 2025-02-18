<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::firstOrCreate(
            ['email' => 'admin@email.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('12345678'),
                'role' => 'admin', // Defina o campo role como admin
                'unidade_fk' => 1, // Certifique-se de substituir pelo ID da unidade apropriada
                'cpf' => '000.000.000-00', // Adicione o campo CPF
            ]
        );
    }
}

