<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleSolicitud extends Model
{
    //Modelo que hace referencia a la tabla detalledesolicitud

    protected $table = 'detalle_solicituds';
    protected $fillable = ['cantidad','idProducto','idSolicitud','comentario'];

    //aqui se declararon la relaciones
    public function Producto(){
        return $this->belongsTo('App\Producto');
    }
    public  function Solicitud(){
        return $this->belongsTo('App\Solicitud');
    }

}
