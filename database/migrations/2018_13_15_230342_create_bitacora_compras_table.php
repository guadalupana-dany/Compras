<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBitacoraComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bitacora_compras', function (Blueprint $table) {
            $table->increments('id');
            $table->string('proveedor',150);
            $table->string('producto',150);
            $table->integer('cantidad');
            $table->decimal('total_pagar',11,2);
            $table->decimal('precio_unitario',11,2);
            $table->dateTime('fecha_compra');
            $table->integer('idUser')->unsigned();
            $table->foreign('idUser')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bitacora_compras');
    }
}
