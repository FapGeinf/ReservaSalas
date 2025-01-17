<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unidade;

class UnidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unidades = [ 
            [ 
                'nome' => 'GEINF - Gerência de Informática', 
                'sigla' => 'GEINF' 
            ], 
            [ 
                'nome' => 'NUAQ - Arquivo',
                 'sigla' => 'NUAQ' 
                
                ],
                [ 
                    'nome' => 'NUCB - Contabilidade', 
                    'sigla' => 'NUCB' 
                    
                ], 

                [
                    'nome' => 'NUCT - Contrato',
                    'sigla' => 'NUCT' 
                ],

                [
                    'nome' => 'NUCV - Convênios',
                    'sigla' => 'NUCV' 
                ],

                [
                    'nome' => 'DAF - Diretoria Administrativo-Financeira',
                    'sigla' => 'DAF' 
                ],

                [
                    'nome' => 'DEAC - Departamento de Acompanhamento e Avaliação',
                    'sigla' => 'DEAC' 
                ],

                [
                    'nome' => 'DEAP - Departamento de Análise de Projetos',
                    'sigla' => 'DEAP' 
                ],

                [
                    'nome' => 'DECON - Departamento de Comunicação e Difusão do Conhecimento',
                    'sigla' => 'DECON' 
                ],

                [
                    'nome' => 'DEOF - Departamento de Operações e Fomento',
                    'sigla' => 'DEOF' 
                ],

                [
                    'nome' => 'DITEC - Diretoria Técnico-Científica',
                    'sigla' => 'DITEC' 
                ],

                [
                    'nome' => 'GABINETE',
                    'sigla' => '' 
                ],

                [
                    'nome' => 'GEAL - Gerência de Apoio Logístico',
                    'sigla' => 'GEAL' 
                ],

                [
                    'nome' => 'PSICOSSOCIAL',
                    'sigla' => '' 
                ],

                [
                    'nome' => 'GEFI - Gerência Financeira',
                    'sigla' => 'GEFI' 
                ],

                [
                    'nome' => 'GEOR - Gerência de Orçamento',
                    'sigla' => 'GEOR' 
                ],

                [
                    'nome' => 'GEPE - Gerência de Gestão de Pessoal',
                    'sigla' => 'GEPE' 
                ],

                [
                    'nome' => 'ASJUR - Jurídico',
                    'sigla' => 'ASJUR' 
                ],

                [
                    'nome' => 'NUPA - Patrimônio',
                    'sigla' => 'NUPA' 
                ],

                [
                    'nome' => 'NUPC - Prestação de Contas',
                    'sigla' => 'NUPC' 
                ],

                [
                    'nome' => 'Sec. Conselhos',
                    'sigla' => '' 
                ],

                [
                    'nome' => 'Ouvidoria',
                    'sigla' => '' 
                ],

                [
                    'nome' => 'UCI - Unidade de Controle Interno',
                    'sigla' => 'UCI' 
                ],

                [
                    'nome' => 'Assessoria da Presidência',
                    'sigla' => '' 
                ],

                [
                    'nome' => 'DEPLAVI - Departamento de Planejamento e Avaliação Institucional',
                    'sigla' => 'DEPLAVI' 
                ],
   
            ]; 
            
            // Ordenar o array em ordem alfabética pelo nome 
            usort($unidades, function ($a, $b) { 
                return strcmp($a['nome'], $b['nome']);

            });

            foreach ($unidades as $unidade) { 
                Unidade::firstOrCreate( 
                    ['sigla' => $unidade['sigla']], 
                    ['nome' => $unidade['nome']] 
                );
    }
}
}
