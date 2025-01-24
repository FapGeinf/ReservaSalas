<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'cpf' => '000.000.000-00',
                'unidade_fk' => 1, // Certifique-se de que a unidade com ID 1 existe
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'cpf' => '111.111.111-11',
                'unidade_fk' => 2, // Certifique-se de que a unidade com ID 2 existe
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Adicione mais usuários conforme necessário
        ]);
    }
}
