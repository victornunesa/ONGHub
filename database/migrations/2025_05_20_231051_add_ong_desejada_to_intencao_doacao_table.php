<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOngDesejadaToIntencaoDoacaoTable extends Migration
{
    public function up()
    {
        Schema::table('intencao_doacao', function (Blueprint $table) {
            $table->unsignedBigInteger('ong_desejada')
                  ->nullable()
                  ->after('telefone_solicitante');
                  
            $table->foreign('ong_desejada')
                  ->references('id')
                  ->on('ong')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('intencao_doacao', function (Blueprint $table) {
            // Remover a foreign key primeiro
            $table->dropForeign(['ong_desejada']);
            
            // Depois remover a coluna
            $table->dropColumn('ong_desejada');
        });
    }
}