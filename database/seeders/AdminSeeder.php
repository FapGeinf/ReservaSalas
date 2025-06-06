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
                'name' => 'Admin',
                'email' => 'admin@email.com',
                'login' => 'admin',
                'password' => '12345678',
                'role' => 'admin',
                'unidade_fk' => 1,
                'cpf' => '000.000.000-00',
            ],
            [
                'name' => 'Joaliny Furtado',
                'email' => 'joaliny@email.com',
                'login' => 'jfurtado',
                'password' => '12345678',
                'role' => 'admin',
                'unidade_fk' => 2,
                'cpf' => '892.883.512-72',
            ],
            [
                'name' => 'Admin1',
                'email' => 'admin1@email.com',
                'login' => 'admin1',
                'password' => '12345678',
                'role' => 'admin',
                'unidade_fk' => 2,
                'cpf' => '000.000.000-04',
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
