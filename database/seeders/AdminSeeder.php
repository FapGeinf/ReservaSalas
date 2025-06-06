<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $admins = [
            [
                'name' => 'Felipe Santos',
                'email' => 'felipe@email.com',
                'login' => 'fsantos',
                'password' => '12345678',
                'role' => 'admin',
                'unidade_fk' => 12,
                'cpf' => '000.000.000-00',
            ],
            [
                'name' => 'Andrey Azevedo',
                'email' => 'andrey@email.com',
                'login' => 'aazevedo',
                'password' => '12345678',
                'role' => 'admin',
                'unidade_fk' => 12,
                'cpf' => '000.000.000-00',
            ],
            [
                'name' => 'Sabrina Simoes',
                'email' => 'sabrina@email.com',
                'login' => 'ssimoes',
                'password' => '12345678',
                'role' => 'admin',
                'unidade_fk' => 12,
                'cpf' => '000.000.000-00',
            ],
            [
                'name' => 'Lucas Silva',
                'email' => 'lucas@email.com',
                'login' => 'lsilva',
                'password' => '12345678',
                'role' => 'admin',
                'unidade_fk' => 12,
                'cpf' => '000.000.000-00',
            ],
             [
                'name' => 'Joao Moraes',
                'email' => 'joao@email.com',
                'login' => 'jmoraes',
                'password' => '12345678',
                'role' => 'admin',
                'unidade_fk' => 12,
                'cpf' => '000.000.000-00',
            ],
            [
                'name' => 'Emerson Cardoso',
                'email' => 'emerson@email.com',
                'login' => 'ecardoso',
                'password' => '12345678',
                'role' => 'admin',
                'unidade_fk' => 12,
                'cpf' => '000.000.000-00',
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@email.com',
                'login' => 'admin',
                'password' => '12345678',
                'role' => 'admin',
                'unidade_fk' => 1,
                'cpf' => '000.000.000-00',
            ],
        ];

        foreach ($admins as $adminData) {
            User::updateOrCreate(
                ['login' => $adminData['login']], // Usa o login como chave principal
                [
                    'name' => $adminData['name'],
                    'email' => $adminData['email'],
                    'password' => Hash::make($adminData['password']),
                    'role' => $adminData['role'],
                    'unidade_fk' => $adminData['unidade_fk'],
                    'cpf' => $adminData['cpf'],
                ]
            );
        }
    }
}
