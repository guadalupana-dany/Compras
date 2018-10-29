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
    }
}
