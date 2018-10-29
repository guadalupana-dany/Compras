<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleSolicitudsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_solicituds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cantidad');
            $table->integer('idSolicitud')->unsigned();
            $table->integer('idProducto')->unsigned();
            $table->string('comentario',50);
            $table->foreign('idSolicitud')->references('id')->on('solicituds')->onDelete('cascade');
            $table->foreign('idProducto')->references('id')->on('productos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_solicituds');
    }
}
