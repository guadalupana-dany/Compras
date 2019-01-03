<?php

namespace App\Exports;

use App\Solicitud;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use App\DetalleSolicitud;

class DetalleExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $fechaInicio;
    private $fechaFin;
    private $user;
    private $estado;
    private $orden;
    //creamos este constructor para recibir las variables del controller solicitud
    public function __construct($fechaInicio,$fechaFin,$user,$estado,$orden)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->user = $user;
        $this->estado = $estado;
        $this->orden = $orden;
    }
    //metodo que genera el archivo xlsx para ser exportado desde la vista para generar reportes
    public function collection()
    {

        $solicitud = DetalleSolicitud::join('productos','detalle_solicituds.IdProducto','=','productos.id')
        ->join('solicituds','detalle_solicituds.idSolicitud','=','solicituds.id')
        ->join('users','solicituds.IdUser','=','users.id')
        ->join('agencia_departamento','solicituds.idAge_depto','=','agencia_departamento.id')
        ->join('agencias','agencia_departamento.agencia_id','=','agencias.id')
        ->join('departamentos','agencia_departamento.departamento_id','=','departamentos.id')
        ->select('solicituds.num_orden','productos.nombre as nombre_producto','detalle_solicituds.cantidad','detalle_solicituds.precio_total','detalle_solicituds.comentario',
        'users.name as nombre_user','solicituds.fecha_hora as fecha','agencias.nombre as nombre_agencia',
        'departamentos.nombre as departamento_nombre');

        if($this->fechaInicio and $this->fechaFin){
                $solicitud
                ->where('solicituds.fecha_hora','>=',$this->fechaInicio)
                ->where('solicituds.fecha_hora','<=',$this->fechaFin);
            }

        if($this->estado != 3){
            $solicitud->where('solicituds.status','=',$this->estado);
            }

        if($this->user){
            $solicitud->where('users.id','=',$this->user);
        }
        if($this->orden != 0){
        //  \Log::info($orden);
          $solicitud->where('solicituds.num_orden','=',$this->orden);
        }


        $solicitud = $solicitud->get();
        return $solicitud;

    }
}
