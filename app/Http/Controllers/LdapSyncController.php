<?php

namespace App\Http\Controllers;

use App\Models\User;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;

class LdapSyncController extends Controller
{
    public function syncUsersWithLdap()
    {
        $ldapUsers = LdapUser::all();  // Pega todos os usuários do LDAP

        foreach ($ldapUsers as $ldapUser) {
            // Cria ou atualiza o usuário no banco de dados
            User::updateOrCreate(
                ['username' => $ldapUser->getSamAccountName()],
                [
                    'name' => $ldapUser->getDisplayName(),
                    'email' => $ldapUser->getEmail(),
                    'password' => bcrypt('senha_padrão'),  // Senha padrão ou lógica para gerar uma
                    'ldap_uid' => $ldapUser->getSid(),
                ]
            );
        }

        return response()->json(['message' => 'Usuários sincronizados com sucesso']);
    }
}


// app/Http/Controllers/LdapSyncController.php

// namespace App\Http\Controllers;

// use App\Models\User;
// use LdapRecord\Models\ActiveDirectory\User as LdapUser;

// class LdapSyncController extends Controller
// {
//     public function syncUsersWithLdap()
//     {
//         // Código para sincronizar os usuários do LDAP
//         $ldapUsers = LdapUser::all();

//         foreach ($ldapUsers as $ldapUser) {
//             User::updateOrCreate(
//                 ['username' => $ldapUser->getSamAccountName()],
//                 [
//                     'name' => $ldapUser->getDisplayName(),
//                     'email' => $ldapUser->getEmail(),
//                     'password' => bcrypt('senha_padrão'),
//                 ]
//             );
//         }

//         return 'Usuários sincronizados com sucesso!';
//     }
// }

