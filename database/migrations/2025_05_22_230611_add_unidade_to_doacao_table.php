<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('doacao', function (Blueprint $table) {
            $table->string('unidade', 10)->after('quantidade');
        });
    }

    public function down()
    {
        Schema::table('doacao', function (Blueprint $table) {
            $table->dropColumn('unidade');
        });
    }
};
