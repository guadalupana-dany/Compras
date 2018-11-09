<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    //Modelo de Departamento

    //funcion que hace relacion de muchos a muchos con agencia
    public function Agencia(){
        return $this->belongsToMany('App\Agencia','agencia_departamento');
    }
}
