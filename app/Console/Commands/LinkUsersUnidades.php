<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Unidade;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;
use LdapRecord\Models\ActiveDirectory\OrganizationalUnit as LdapOu;
use Symfony\Component\Console\Command\Command as CommandAlias;
use Illuminate\Support\Str;
use Exception;

class LinkUsersUnidades extends Command
{
    protected $signature = 'ad:link-users-unidades {--debug : Exibe detalhes de cada usuário processado}';

    protected $description = 'Extrai a Unidade (OU/Departamento) do usuário do LDAP e realiza o vínculo no MySQL';

    public function handle()
    {
        $this->info('Iniciando extração de unidades dos usuários no LDAP...');

        $users = User::all();

        if ($users->isEmpty()) {
            $this->warn('Nenhum usuário encontrado na base de dados.');
            return CommandAlias::SUCCESS;
        }

        $updatedCount = 0;
        $notFoundCount = 0;
        $noOuCount = 0;
        
        // Arrays para armazenar os nomes para o relatório final
        $usersWithoutUnit = [];
        $usersNotFound = [];

        $this->output->progressStart($users->count());

        foreach ($users as $user) {
            $ldapUser = null;

            // 1. Tenta buscar pelo GUID usando o helper nativo do LdapRecord
            if (!empty($user->guid)) {
                try {
                    $ldapUser = LdapUser::findByGuid($user->guid);
                } catch (Exception $e) {
                    $ldapUser = null;
                }
            }

            // 2. Fallback: Se não achar pelo GUID, tenta pelo e-mail
            if (!$ldapUser && !empty($user->email)) {
                $ldapUser = LdapUser::where('mail', $user->email)->first();
            }

            // 3. Fallback: Tenta pelo nome de usuário / login
            if (!$ldapUser && !empty($user->username)) {
                $ldapUser = LdapUser::where('samaccountname', $user->username)->first();
            }

            // Se não encontrou o usuário no Active Directory
            if (!$ldapUser) {
                $notFoundCount++;
                $usersNotFound[] = $user->name;
                
                if ($this->option('debug')) {
                    $this->newLine();
                    $this->warn("Usuário [{$user->name}] não foi localizado no LDAP.");
                }
                $this->output->progressAdvance();
                continue;
            }

            // Tenta pegar pelo atributo 'department' do AD
            $nomeUnidade = $ldapUser->getFirstAttribute('department');

            // Fallback: Se 'department' estiver vazio, extrai a PRIMEIRA OU do caminho DN (ex: OU=DITEC)
            if (!$nomeUnidade) {
                $dn = $ldapUser->getDn();
                if (preg_match('/OU=([^,]+)/i', $dn, $matches)) {
                    $nomeUnidade = $matches[1]; // Pega 'DITEC'
                }
            }

            // Se encontrou alguma unidade válida
            if ($nomeUnidade) {
                $nomeFormatado = trim($nomeUnidade);

                // 1. Tenta buscar a Unidade no MySQL pelo nome
                $unidade = Unidade::where('nome', $nomeFormatado)->first();

                // 2. Se não existir no MySQL, cria com todos os campos obrigatórios
                if (!$unidade) {
                    $adOu = LdapOu::where('ou', $nomeFormatado)->first();
                    $guidOu = $adOu ? $adOu->getConvertedGuid() : (string) Str::uuid();

                    $unidade = Unidade::create([
                        'nome'    => $nomeFormatado,
                        'sigla'   => mb_strtoupper($nomeFormatado),
                        'ad_guid' => $guidOu,
                        'dn'      => $adOu ? $adOu->getDn() : null,
                        'active'  => 1,
                    ]);
                }

                // Associa a chave estrangeira no usuário
                $user->unidade_fk = $unidade->id;

                // Aproveita para salvar/atualizar o guid no MySQL caso estivesse ausente
                if (empty($user->guid)) {
                    $user->guid = $ldapUser->getGuid();
                }

                $user->save();
                $updatedCount++;
            } else {
                $noOuCount++;
                $usersWithoutUnit[] = $user->name; // Armazena o nome do usuário sem unidade/OU

                if ($this->option('debug')) {
                    $this->newLine();
                    $this->warn("Usuário [{$user->name}] encontrado no AD, mas sem 'department' ou 'OU' definida.");
                }
            }

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();

        // Exibe o resumo final no terminal
        $this->newLine();
        $this->info("=== RESUMO DA EXECUÇÃO ===");
        $this->info("✅ Vinculados com sucesso: {$updatedCount}");
        
        if ($notFoundCount > 0) {
            $this->warn("⚠️  Não encontrados no AD: {$notFoundCount}");
        }
        
        if ($noOuCount > 0) {
            $this->warn("⚠️  Sem unidade/OU no AD: {$noOuCount}");
            $this->line("--- Lista de usuários sem unidade/OU ---");
            foreach ($usersWithoutUnit as $userName) {
                $this->line("  - {$userName}");
            }
        }

        return CommandAlias::SUCCESS;
    }
}