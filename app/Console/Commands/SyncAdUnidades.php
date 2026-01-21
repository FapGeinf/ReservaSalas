<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use LdapRecord\Models\ActiveDirectory\OrganizationalUnit;
use App\Models\Unidade;

class SyncAdUnidades extends Command
{
    protected $signature = 'ad:sync-unidades';

    protected $description = 'Sincroniza Unidades (OUs) do Active Directory';

    public function handle()
    {
        $this->info('Buscando OUs no Active Directory...');

        
        $baseDn = env('LDAP_UNIDADES_BASE_DN', null);

        if (!$baseDn) {
            $this->error('LDAP_UNIDADES_BASE_DN não definido no .env');
            return self::FAILURE;
        }

        
        $ous = OrganizationalUnit::in($baseDn)->get();

        $this->info("Encontradas {$ous->count()} OUs");

        foreach ($ous as $ou) {

            $nome = $ou->getFirstAttribute('ou');
            $dn   = $ou->getDn();
            $guid = $ou->getConvertedGuid();

            if (!$nome || !$guid) {
                $this->warn("OU ignorada (dados incompletos): {$dn}");
                continue;
            }

            Unidade::updateOrCreate(
                ['ad_guid' => $guid],
                [
                    'nome'   => $nome,
                    'sigla'  => mb_strtoupper($nome), 
                    'dn'     => $dn,
                    'active' => 1,
                ]
            );

            $this->line("Unidade sincronizada: {$nome}");
        }

        $this->info('Sincronização de unidades concluída');

        return self::SUCCESS;
    }
}
