<?php
namespace App\Http\Controllers;

use App\Models\User;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use Illuminate\Http\Request;

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



// namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use LdapRecord\Models\ActiveDirectory\User as LdapUser;
// use App\Models\User; // Modelo de usuários no banco de dados

// class LoginController extends Controller
// {
//     // Exibe o formulário de login
//     public function showLoginForm()
//     {
//         return view('auth.login');
//     }

//     // Processa o login
//     public function login(Request $request)
//     {
//         // Validar os dados de entrada
//         $credentials = $request->only('username', 'password');

//         // Verificar no LDAP
//         try {
//             $ldapUser = LdapUser::where('samaccountname', $credentials['username'])->first(); // Busca o usuário no LDAP

//             // Verifica se o usuário foi encontrado no LDAP
//             if ($ldapUser && $ldapUser->auth($credentials['password'])) { // Usar o método 'auth' do LDAP para validar a senha
//                 // Se o login for bem-sucedido, cria o usuário no banco de dados (se necessário)
//                 $user = User::firstOrCreate([
//                     'username' => $ldapUser->samaccountname,
//                     'name' => $ldapUser->getDisplayName(), // Pega o nome do usuário no LDAP
//                     'email' => $ldapUser->getMail(), // Pega o email do usuário no LDAP
//                 ]);
                
//                 // Autentica o usuário no sistema
//                 Auth::login($user);
//                 return redirect()->intended('/home');
//             }
//         } catch (\Exception $e) {
//             // Se falhar no LDAP, tenta autenticar com CPF no banco de dados
//             // Você pode tratar o erro do LDAP para que ele não quebre a aplicação
//         }

//         // Caso falhe no LDAP, tenta autenticação com CPF (no banco de dados)
//         if (Auth::attempt(['cpf' => $credentials['username'], 'password' => $credentials['password']])) {
//             return redirect()->intended('/home');
//         }

//         // Se não conseguir autenticar com nenhum dos dois
//         return back()->withErrors(['login' => 'Usuário ou senha inválidos']);
//     }

//     // Função para realizar o logout
//     public function logout(Request $request)
//     {
//         Auth::logout();
//         $request->session()->invalidate();
//         $request->session()->regenerateToken();
//         return redirect('/login');
//     }
// }
