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
        Schema::create('pedido_doacao_ong_pivot', function (Blueprint $table) {
            $table->foreignId('pedido_id')->constrained('pedido_doacao');
            $table->foreignId('ong_id')->constrained('ong');
            $table->primary(['pedido_id', 'ong_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_doacao_ong_pivot');
    }
};
