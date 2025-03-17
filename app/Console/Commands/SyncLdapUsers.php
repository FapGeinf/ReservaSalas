<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use App\Models\User;

class SyncLdapUsers extends Command
{
    protected $signature = 'ldap:sync-users';
    protected $description = 'Sincronizar usuários do LDAP com o banco de dados local';

    public function handle()
    {
        // Buscar todos os usuários no LDAP
        $users = LdapUser::all();  

        foreach ($users as $ldapUser) {
            // Mapear dados do LDAP para o modelo do Laravel e atualizar/registrar no banco
            User::updateOrCreate(
                ['ldap_uid' => $ldapUser->getSid()],  // Identificador único do LDAP
                [
                    'name' => $ldapUser->getDisplayName(),  // Nome do usuário
                    'email' => $ldapUser->getEmail(),  // E-mail do usuário
                    'password' => bcrypt('senha_padrão'),  // Senha padrão ou lógica para gerar uma
                    'ldap_uid' => $ldapUser->getSid(),  // SID ou UID do LDAP
                    'role' => 'user',  // Atribuir um valor de role padrão (você pode modificar conforme a lógica de autenticação)
                    'unidade_fk' => null,  // Definir unidade ou outro valor conforme sua necessidade
                    'image' => null,  // Defina ou deixe null, caso queira tratar imagens
                    'cpf' => null,  // CPF pode ser atualizado ou removido conforme sua necessidade
                ]
            );
        }

        $this->info('Usuários sincronizados com sucesso!');
    }
}

// namespace App\Console\Commands;

// use Illuminate\Console\Command;
// use LdapRecord\Models\ActiveDirectory\User as LdapUser;
// use App\Models\User;

// class SyncLdapUsers extends Command
// {
//     protected $signature = 'ldap:sync-users';
//     protected $description = 'Sincroniza os usuários do LDAP com o sistema.';

//     public function __construct()
//     {
//         parent::__construct();
//     }

//     public function handle()
//     {
//         $this->info('Sincronizando usuários do LDAP...');

//         try {
//             // Buscar todos os usuários do LDAP
//             $ldapUsers = LdapUser::all();

//             foreach ($ldapUsers as $ldapUser) {
//                 // Verificar se o usuário já existe no banco de dados
//                 $user = User::updateOrCreate(
//                     ['username' => $ldapUser->samaccountname], // Chave única, pode ser adaptada
//                     [
//                         'name' => $ldapUser->getDisplayName(),
//                         'email' => $ldapUser->getMail(),
//                         // Outras informações que você queira sincronizar
//                     ]
//                 );

//                 $this->info("Usuário {$ldapUser->samaccountname} sincronizado com sucesso.");
//             }

//             $this->info('Sincronização concluída.');
//         } catch (\Exception $e) {
//             $this->error('Erro ao sincronizar usuários: ' . $e->getMessage());
//         }
//     }
// }
