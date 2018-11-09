<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',30)->unique();
            $table->string('descripcion',100)->nullable();
            $table->boolean('estado')->default(1);
        });
        DB::table('roles')->insert(array('id' => 1, 'nombre' => 'Administrador','descripcion'=>'Solo el admin podra tener permiso'));
        DB::table('roles')->insert(array('id' => 2, 'nombre' => 'Verificador','descripcion'=>'Este rol le pertenece a la área de compras'));
        DB::table('roles')->insert(array('id' => 3, 'nombre' => 'Departamento','descripcion'=>'Este rol es para los jefes de departamento'));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
