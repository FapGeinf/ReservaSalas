<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_imagem_to_salas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagemToSalasTable extends Migration
{
    public function up()
    {
        Schema::table('salas', function (Blueprint $table) {
            // Adiciona uma coluna 'imagem' para armazenar o nome da imagem ou o caminho
            // $table->string('imagem')->nullable();  // A coluna pode ser nula inicialmente
        });
    }

    public function down()
    {
        Schema::table('salas', function (Blueprint $table) {
            // Caso precise reverter a migração, remove a coluna 'imagem'
            $table->dropColumn('imagem');
        });
    }
}
