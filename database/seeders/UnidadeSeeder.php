<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unidade;
use LdapRecord\Models\ActiveDirectory\OrganizationalUnit;

class UnidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Busca a Base DN do .env ou usa o padrão da FAPEAM
        $baseDn = env('LDAP_UNIDADES_BASE_DN', 'OU=FAPEAM,DC=fapeam,DC=local');

        $adUnidades = OrganizationalUnit::in($baseDn)->get();

        if ($adUnidades->isEmpty()) {
            $this->command->warn("Nenhuma OU encontrada na base: {$baseDn}");
            return;
        }

        foreach ($adUnidades as $adUnidade) {
            // Pega o GUID já convertido em string
            $guid = $adUnidade->getConvertedGuid(); 

            // Pega o nome da OU (ex: "DEAP", "GEINF", "GABINETE DA PRESIDENCIA")
            $nome = $adUnidade->getFirstAttribute('ou') ?? $adUnidade->getFirstAttribute('name');
            $dn   = $adUnidade->getDn();

            if (!$nome || !$guid) {
                continue;
            }

            Unidade::updateOrCreate(
                ['ad_guid' => $guid],
                [
                    'nome'   => $nome,
                    'sigla'  => mb_strtoupper($nome), // Ex: DEAP, GEINF
                    'dn'     => $dn,
                    'active' => 1,
                ]
            );

            $this->command->info("Unidade importada: {$nome}");
        }
    }
}