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
        Schema::create('doacao_estoque_pivot', function (Blueprint $table) {
            $table->foreignId('doacao_id')->constrained('doacao');
            $table->foreignId('estoque_id')->constrained('estoque');
            $table->integer('quantidade');
            $table->primary(['doacao_id', 'estoque_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doacao_estoque_pivot');
    }
};
