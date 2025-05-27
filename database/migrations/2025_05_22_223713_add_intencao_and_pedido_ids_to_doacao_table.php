<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIntencaoAndPedidoIdsToDoacaoTable extends Migration
{
    public function up()
    {
        Schema::table('doacao', function (Blueprint $table) {
            $table->unsignedBigInteger('intencao_id')->nullable()->after('id');
            $table->unsignedBigInteger('pedido_id')->nullable()->after('intencao_id');
            
            $table->foreign('intencao_id')->references('id')->on('intencao_doacao')->onDelete('set null');
            $table->foreign('pedido_id')->references('id')->on('pedido_doacao')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('doacao', function (Blueprint $table) {
            $table->dropForeign(['intencao_id']);
            $table->dropForeign(['pedido_id']);
            $table->dropColumn(['intencao_id', 'pedido_id']);
        });
    }
}
