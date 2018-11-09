<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = \App\Rol::where('nombre', 'Administrador')->first();
        $user = new \App\User();
        $user->name = 'Dany Diaz';
        $user->email = 'dany.diaz@micoopeguadalupana.com.gt';
        $user->password = bcrypt('admin123!');
        $user->save();
        $user->roles()->attach($role_admin);

        $Verificador = \App\Rol::where('nombre', 'Verificador')->first();
        $user = new \App\User();
        $user->name = 'Berenice GArcia';
        $user->email = 'berenice.garcia@micoopeguadalupana.com.gt';
        $user->password = bcrypt('micoope123');
        $user->save();
        $user->roles()->attach($Verificador);

        $Departamento = \App\Rol::where('nombre', 'Departamento')->first();
        $user = new \App\User();
        $user->name = 'Manuel Puaque';
        $user->email = 'manuel.puaque@micoopeguadalupana.com.gt';
        $user->password = bcrypt('micoope321');
        $user->save();
        $user->roles()->attach($Departamento);
        //verificador micoope123
        //departamento micoope321
    }
}
