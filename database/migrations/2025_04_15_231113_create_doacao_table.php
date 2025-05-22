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
        Schema::create('doacao', function (Blueprint $table) {
            $table->id();

            // Definindo como nullable desde a criação
            $table->unsignedBigInteger('ong_origem_id')->nullable();
            $table->unsignedBigInteger('ong_destino_id')->nullable();
            
            // Chaves estrangeiras com suporte a null
            $table->foreign('ong_origem_id')->references('id')->on('ong')->onDelete('set null');
            $table->foreign('ong_destino_id')->references('id')->on('ong')->onDelete('set null');

            //$table->foreignId('ong_origem_id')->constrained('ong');
            //$table->foreignId('ong_destino_id')->constrained('ong');
            $table->string('nome_doador', 100);
            $table->string('email_doador', 150);
            $table->string('telefone_doador', 15);
            $table->text('descricao');
            $table->integer('quantidade');
            $table->date('data_doacao');
            $table->string('status', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doacao');
    }
};
