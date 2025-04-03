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
                'login' => 'admin', // Adicione o campo login
                'password' => bcrypt('12345678'),
                'role' => 'admin', // Defina o campo role como admin
                'unidade_fk' => 1, // Certifique-se de substituir pelo ID da unidade apropriada
                'cpf' => '000.000.000-00', // Adicione o campo CPF
            ]
        );

        //  // Segundo usuário admin
        //  User::firstOrCreate(
        //     ['email' => 'joaliny@email.com'],
        //     [
        //         'name' => 'joaliny Furtado',
        //         'login' => 'jfurtado',
        //         'password' => bcrypt('12345678'),
        //         'role' => 'admin',
        //         'unidade_fk' => 2, // Certifique-se de substituir pelo ID da unidade apropriada
        //         'cpf' => '892.883.512-72', // Adicione o campo CPF
        //     ]
        // );

        // Terceiro usuário admin
        User::firstOrCreate(
            ['email' => 'admin1@email.com'],
            [
                'name' => 'Admin1',
                'login' => 'admin1',
                'password' => bcrypt('12345678'),
                'role' => 'admin',
                'unidade_fk' => 2, // Certifique-se de substituir pelo ID da unidade apropriada
                'cpf' => '000.000.000-04', // Adicione o campo CPF
            ]
        );

    }
}

    

