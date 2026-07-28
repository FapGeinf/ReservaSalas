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
        Schema::table('unidades', function(Blueprint $table){
            $table->text('dn')->nullable()->after('sigla');
            $table->char('ad_guid',36)->nulldable()->after('dn');
            $table->boolean('active')->default(true)->after('ad_guid');
            
            $table->unique('ad_guid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unidades', function(Blueprint $table){
                $table->dropUnique(['ad_guid']);
                $table->dropColumn(['dn','ad_guid','active']);
        });
    }
};
