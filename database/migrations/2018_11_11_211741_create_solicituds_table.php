<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolicitudsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicituds', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('fecha_hora');
            $table->string('nombre_solcitante',50);
            $table->integer('idAge_depto')->unsigned();
            $table->boolean('status')->default(1);
            $table->foreign('idAge_depto')->references('id')->on('agencia_departamento')->onDelete('cascade');
            $table->string('comentario',200);
            $table->integer("num_orden");
            $table->decimal('total_gasto', 11,2)->nullable();
            $table->integer('idUser')->unsigned();
            $table->foreign('idUser')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('solicituds');
    }
}
