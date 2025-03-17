<?php

namespace App\Http\Controllers;

use App\Models\User;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use Illuminate\Http\Request;

class LdapSyncController extends Controller
{
    public function syncUsersWithLdap()
    {
        try {
            $ldapUsers = LdapUser::all();

            foreach ($ldapUsers as $ldapUser) {
                User::updateOrCreate(
                    ['username' => $ldapUser->getSamAccountName()],
                    [
                        'name' => $ldapUser->getDisplayName(),
                        'email' => $ldapUser->getEmail(),
                        'password' => bcrypt('senha_padrão'),
                        'ldap_uid' => $ldapUser->getSid(),
                    ]
                );
            }

            return response()->json(['message' => 'Usuários sincronizados com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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

