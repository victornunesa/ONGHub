<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Verifica se a coluna existe antes de tentar modificá-la
        if (Schema::hasColumn('pedido_doacao', 'unidade')) {
            Schema::table('pedido_doacao', function (Blueprint $table) {
                // Altera a coluna existente para garantir as configurações corretas
                $table->string('unidade', 10)->default('un')->change();
            });
        } else {
            Schema::table('pedido_doacao', function (Blueprint $table) {
                $table->string('unidade', 10)->after('quantidade')->default('un');
            });
        }
    }

    public function down()
    {
        Schema::table('pedido_doacao', function (Blueprint $table) {
            $table->dropColumn('unidade');
        });
    }
};
