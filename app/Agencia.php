<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agencia extends Model
{
    //este modelo hace regerencia a la tabla agencia de la bd
   protected $fillable = ['nombre'];

    public function Departamento(){
        return $this->belongsToMany('App\Departamento','agencia_departamento');
    }

}
