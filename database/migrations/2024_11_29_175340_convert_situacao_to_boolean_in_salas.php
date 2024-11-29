<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ConvertSituacaoToBooleanInSalas extends Migration
{
    public function up()
    {
        Schema::table('salas', function (Blueprint $table) {
            // Adicionar uma nova coluna temporária
            $table->boolean('situacao_temp')->default(false);
        });

        // Atualizar a nova coluna com base nos valores da coluna antiga
        DB::table('salas')->update(['situacao_temp' => DB::raw("IF(situacao = 'ativa', 1, 0)")]);

        Schema::table('salas', function (Blueprint $table) {
            // Remover a coluna antiga
            $table->dropColumn('situacao');
            // Renomear a coluna temporária para a original
            $table->renameColumn('situacao_temp', 'situacao');
        });
    }

    public function down()
    {
        Schema::table('salas', function (Blueprint $table) {
            // Adicionar a coluna antiga de volta
            $table->string('situacao')->default('inativa');
        });

        // Reverter os valores da coluna antiga com base nos valores booleanos
        DB::table('salas')->update(['situacao' => DB::raw("IF(situacao = 1, 'ativa', 'inativa')")]);

        Schema::table('salas', function (Blueprint $table) {
            // Remover a coluna boolean
            $table->dropColumn('situacao');
            // Renomear a coluna temporária de volta
            $table->renameColumn('situacao_temp', 'situacao');
        });
    }
}

