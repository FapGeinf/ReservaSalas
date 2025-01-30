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
                'is_admin' => true,
            ]
        );
    }
}

