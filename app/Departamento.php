<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    public function Agencia(){
        return $this->belongsToMany('App\Agencia','agencia_departamento');
    }
}
