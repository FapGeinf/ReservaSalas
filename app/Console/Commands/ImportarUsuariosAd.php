<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ImportarUsuariosAd extends Command
{
    protected $signature = 'ldap:importar-usuarios';
    protected $description = 'Importa usuários do AD (Windows Server 2008) para o banco de dados';

    public function handle()
    {
        $ldapServer = "ldap://172.16.1.4"; // ex: 192.168.0.1
        $ldapPort = 389;
        $ldapUser = "fap\\admin"; // ex: FAPEAM\\admin
        $ldapPass = "12345678";
        $baseDn = "DC=fap,DC=local"; // ex: DC=fapeam,DC=local

        $conn = ldap_connect($ldapServer, $ldapPort);
        ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);

        if (!@ldap_bind($conn, $ldapUser, $ldapPass)) {
            $this->error("Erro ao autenticar no LDAP");
            return 1;
        }

        //Filtro: pega apenas usuários (exclui desativados e objetos desnecessários)
        $filter = "(&(objectClass=user)(objectCategory=person))";
        $result = ldap_search($conn, $baseDn, $filter);
        $entries = ldap_get_entries($conn, $result);

        $importados = 0;

        for ($i = 0; $i < $entries["count"]; $i++) {
            $entry = $entries[$i];

            if (
                isset($entry["samaccountname"][0]) &&
                isset($entry["givenname"][0]) &&
                isset($entry["sn"][0]) &&
                isset($entry["displayname"][0])
            ) {
                // $username = $entry["samaccountname"][0];
                // $firstName = $entry["givenname"][0];
                // $lastName = $entry["sn"][0];
                // $displayName = $entry["displayname"][0];
                $username = $entry["samaccountname"][0];
                $firstName = $entry["givenname"][0];
                $lastName = $entry["sn"][0];
                $displayName = $entry["displayname"][0];
                $email = $username . '@seudominio.com';
                $fullName = $firstName . ' ' . $lastName;

                //Verifica se já existe
                // if (!User::where('username', $username)->exists()) {
                //     User::create([
                //         'username' => $username,
                //         'first_name' => $firstName,
                //         'last_name' => $lastName,
                //         'display_name' => $displayName,
                //         'email' => $username . '@seudominio.com', // opcional
                //         'password' => bcrypt('senha_falsa'), // nunca use a senha do AD real
                //     ]);
                if (!User::where('username', $username)->exists()) {
                    User::create([
                        'username' => $username,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'display_name' => $displayName,
                        'name' => $fullName, // <== CAMPO ADICIONADO
                        'email' => $email,
                        'password' => bcrypt('senha_falsa'), // senha fake
                    ]);

                    $importados++;
                }
            }
        }

        ldap_unbind($conn);

        $this->info("Importação concluída com sucesso. Total de usuários importados: $importados");

        return 0;
    }
}

