<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
        if (!Schema::hasColumn('users', 'username')) {
        		$table->string('username', 191)->nullable()->unique()->after('name');
        }

        if (Schema::hasColumn('users', 'role')) {
            $table->dropColumn('role'); // remove role antigo
        }

        if (!Schema::hasColumn('users', 'tipo')) {
            $table->enum('tipo', ['admin', 'usuario'])->default('usuario')->after('password');
        }

        if (!Schema::hasColumn('users', 'domain')) {
            $table->string('domain', 191)->nullable()->after('tipo');
        }

        if (!Schema::hasColumn('users', 'guid')) {
            $table->char('guid', 36)->nullable()->after('domain');
        }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'tipo', 'domain', 'guid']);
        		$table->string('role')->default('user');
        });
    }
};
