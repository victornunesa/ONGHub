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
        // Adicionar campo na tabela estoque
        Schema::table('estoque', function (Blueprint $table) {
            $table->date('data_validade')->nullable()->after('quantidade');
        });

        // Adicionar campo na tabela doacao
        Schema::table('doacao', function (Blueprint $table) {
            $table->date('data_validade')->nullable()->after('quantidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover campo da tabela estoque
        Schema::table('estoque', function (Blueprint $table) {
            $table->dropColumn('data_validade');
        });

        // Remover campo da tabela doacao
        Schema::table('doacao', function (Blueprint $table) {
            $table->dropColumn('data_validade');
        });
    }
};
