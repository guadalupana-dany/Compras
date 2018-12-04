<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $fillable =['fecha_hora','nombre_solcitante','idAge_depto','status','comentario','fecha_fin'];

    public function DetalleSolicitud(){
        return $this->hasMany('App\DetalleSolicitud');
    }

    public function Usuario(){
        return $this->belongsTo('App\User');
    }


}
