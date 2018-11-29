<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddComentarioRechazoToDetalleSolicituds extends Migration
{

    public function up()
    {
        Schema::table('detalle_solicituds', function (Blueprint $table) {

            $table->String('comenRechazo',200)->nullable();

        });
    }


    public function down()
    {
        //
    }
}
