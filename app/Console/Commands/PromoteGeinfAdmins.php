<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PromoteGeinfAdmins extends Command
{
    protected $signature = 'users:promote-geinf-admins';

    protected $description = 'Define como administradores todos os usuários da unidade GEINF';

    public function handle(): int
    {
        $updated = User::where('unidade_fk', 2)
            ->update([
                'nivel_acesso_id' => 2,
            ]);

        $this->info("{$updated} usuários promovidos para administrador.");

        return self::SUCCESS;
    }
}