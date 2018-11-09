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
            $table->decimal('precio_unitario', 11,2)->nullable();
            $table->decimal('precio_total', 11,2)->nullable();
            $table->integer('idSolicitud')->unsigned();
            $table->integer('idProducto')->unsigned();
            $table->boolean('update_cantidad')->default(0);
            $table->string('comentario',250);
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
