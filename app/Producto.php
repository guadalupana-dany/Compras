<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = ['idCategoria','nombre'];

    public function categorias(){
        return $this->belongsTo('App\Categoria');
    }

    public function detalleSolicitud(){
        return $this->hasMany('App\DetalleSolicitud');
    }
}
