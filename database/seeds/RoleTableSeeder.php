<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new \App\Rol();
        $role->nombre = 'Administrado';
        $role->description = 'Este usuario tendrá acceso a todo el sistema';
        $role->save();
    }
}
