<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateControlBodegaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('control_bodega_', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('can_mes_anterior')->nullable();
            $table->decimal('pre_u_mes_anterior', 11,2)->nullable();
            $table->decimal('tot_mes_anterior', 11,2)->nullable();

            $table->integer('can_mes_actual')->nullable();
            $table->decimal('pre_u_mes_actual', 11,2)->nullable();
            $table->decimal('tot_mes_actual', 11,2)->nullable();

            $table->integer('total_stock')->nullable();
            $table->decimal('total_saldo', 11,2)->nullable();
            $table->decimal('total_unitario', 11,2)->nullable();

            $table->integer('idProducto')->unsigned();
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
        Schema::dropIfExists('control_bodega_');
    }
}
