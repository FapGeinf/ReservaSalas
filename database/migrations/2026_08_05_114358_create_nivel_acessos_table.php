<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('niveis_acessos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->timestamps();
        });

        DB::table('niveis_acessos')->insert([
            ['id' => 1, 'tipo' => 'Usuário Comum', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'tipo' => 'Administrador', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'tipo' => 'Root', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('nivel_acesso_id')
                  ->default(1)
                  ->after('unidade_fk')
                  ->constrained('niveis_acessos');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['nivel_acesso_id']);
            $table->dropColumn('nivel_acesso_id');
        });

        Schema::dropIfExists('niveis_acessos');
    }
};  