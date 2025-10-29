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
        Schema::table('estanques', function (Blueprint $table) {
            $table->unsignedBigInteger('cultivo_pez_id')->nullable()->after('tipo_cultivo');
            $table->foreign('cultivo_pez_id')->references('id')->on('cultivo_peces')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estanques', function (Blueprint $table) {
            $table->dropForeign(['cultivo_pez_id']);
            $table->dropColumn('cultivo_pez_id');
        });
    }
};
