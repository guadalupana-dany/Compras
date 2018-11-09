<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ControlBodega extends Model
{
    //modelo que hace control a la bd control_bodega_
    protected $table = 'control_bodega_';
    protected $fillable = ['can_mes_anterior','pre_u_mes_anterior','tot_mes_anterior','can_mes_actual',
                           'pre_u_mes_actual','tot_mes_actual','total_stock','total_saldo','total_unitario',
                           'idProducto'];

    //funcion que hace la relacion con producto
    public function Producto(){
        return $this->belongsTo('App\Producto');
    }
}
