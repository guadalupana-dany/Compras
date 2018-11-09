<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //modelo que hace referencia a la tabla categoria de la bd
  protected $fillable = ['nombre'];
     public function productos(){
         return $this->hasMany('App\Producto');
     }
}
