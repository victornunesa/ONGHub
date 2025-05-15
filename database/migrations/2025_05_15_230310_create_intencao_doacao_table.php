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
    Schema::create('intencao_doacao', function (Blueprint $table) {
        $table->id();
        $table->string('nome_solicitante', 100);
        $table->string('email_solicitante', 100);
        $table->string('telefone_solicitante', 15);
        $table->text('descricao');
        $table->string('tipo', 50);
        $table->integer('quantidade');
        $table->string('unidade', 10); // Adicione se necessário
        $table->string('status', 50)->default('Pendente');
        $table->date('data_pedido');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intencao_doacao');
    }
};
