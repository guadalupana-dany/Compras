<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agencia extends Model
{
   protected $fillable = ['nombre'];

    public function Departamento(){
        return $this->belongsToMany('App\Departamento','agencia_departamento');
    }

}
