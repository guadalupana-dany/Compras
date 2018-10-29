<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleSolicitud extends Model
{
    protected $table = 'detalle_solicituds';
    protected $fillable = ['cantidad','idProducto','idSolicitud','comentario'];

    public function Producto(){
        return $this->belongsTo('App\Producto');
    }
    public  function Solicitud(){
        return $this->belongsTo('App\Solicitud');
    }

}
