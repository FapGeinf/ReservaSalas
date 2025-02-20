<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('salas', function (Blueprint $table) {
        $table->string('situacao', 10)->change(); // Altera o tipo para VARCHAR
    });
}

public function down()
{
    Schema::table('salas', function (Blueprint $table) {
        $table->integer('situacao')->change(); // Reverte para INT
    });
}
};
