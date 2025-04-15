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
        Schema::create('pedido_doacao', function (Blueprint $table) {
            $table->id();
            $table->string('nome_solicitante', 100);
            $table->string('email_solicitante', 100);
            $table->string('telefone_solicitante', 15);
            $table->text('descricao');
            $table->integer('quantidade');
            $table->string('status', 50);
            $table->date('data_pedido');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_doacao');
    }
};
