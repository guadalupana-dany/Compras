<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->boolean('prioridad_pedido')->default(1);
            $table->string('password');
            $table->boolean('estado')->default(1);
            $table->rememberToken();
            //verificaro micoope123
            //departamento micoope321
        });

       // $user->roles()->attach($request->data);
       // DB::table('users')->insert(array('id' => 1, 'name' => 'Admin','email'=>'admin@gmail.com','password' => bcrypt('admin123') ));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
