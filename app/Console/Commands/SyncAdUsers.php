<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use App\Models\User;

class SyncAdUsers extends Command
{
    protected $signature = 'app:sync-ad-users';
    protected $description = 'Sincroniza usuários do Active Directory para a tabela users local';

    public function handle()
    {
        $this->info('Buscando usuários no AD...');

        $ldapUsers = LdapUser::on('default')
            ->where('objectClass', '=', 'user')
            ->get();

        $this->info("Foram encontrados {$ldapUsers->count()} usuários no AD.");

        foreach ($ldapUsers as $ldapUser) {
						$samAccountName = $ldapUser->getFirstAttribute('samaccountname');
						$displayName    = $ldapUser->getFirstAttribute('displayName');
						$guid           = $ldapUser->getConvertedGuid();

						if (!$samAccountName) continue;

						$user = User::updateOrCreate(
								['login' => $samAccountName], // chave de identificação
								[
										'login'    => $samAccountName,
										'username' => $samAccountName,
										'name'     => $displayName ?? $samAccountName,
										'password' => null, // a senha via ldap deve constar como null
										'tipo'     => 'usuario',
										'is_admin' => 0,
										'domain'   => 'fap.local',
										'guid'     => $guid,
										'auth_provider' => 'ldap',
								]
						);

						$this->info("Usuário sincronizado: {$user->login} ({$user->name})");
				}


        $this->info('✅ Sincronização de usuários concluída.');
    }
}
