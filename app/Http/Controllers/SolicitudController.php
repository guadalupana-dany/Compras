<?php

namespace App\Http\Controllers;

use App\Agencia;
use App\Categoria;
use App\DetalleSolicitud;
use App\Producto;
use App\Solicitud;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class SolicitudController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function guardar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador','Solicitante']);

        if (!$request->ajax()) return redirect('/');
        $retorno = 0;
        try{

            DB::beginTransaction();

            $agencia_departamento = DB::table('agencia_departamento')
                ->where('departamento_id','=',$request->idDepartamento)
                ->where('agencia_id','=',$request->Idagencia)
                ->get();

            foreach($agencia_departamento as $ad){
                //\Log::debug($ad->id);
                $idAge_depto = $ad->id;
            }

            $numOrd = DB::table('solicituds')->select('num_orden')
                ->orderby('created_at','DESC')->take(1)->get();

            foreach($numOrd as $n){
                $numero_orden = $n->num_orden + 1;
            }

            $mytime = Carbon::now('America/Guatemala');
            $solicitud = new Solicitud();
            $solicitud->fecha_hora = $mytime;
            $solicitud->nombre_solcitante = $request->nombre;
            $solicitud->idAge_depto = $idAge_depto;
            $solicitud->comentario = $request->comentarioGeneral;
            $solicitud->idUser = $request->user()->id;
            $solicitud->num_orden = $numero_orden;
            $solicitud->save();

            $productos = $request->arrayDetalleListo;
            foreach($productos as $ep=>$det){
                $detalleS =  new DetalleSolicitud();
                $detalleS->idSolicitud = $solicitud->id;
                $detalleS->cantidad = $det['cantidad'];
                $detalleS->idProducto = $det['idProducto'];
                $detalleS->comentario = $det['comentarioDetalle'];

                $detalleS->save();
            }
            DB::commit();
            $users = $request->user();
            //SE CREA LA PLANTILLA Y [] DENTRO DE ESTO LE PASAMOS LOS PARAMETROS A LA PLANTILLA PARA QUE SEA DINAMICO
            Mail::send('emails.reminder',['users' => $users,'numero_orden' => $numero_orden],function($m) use ($users){
                //AQUIEN MANDA EL CORREO
                $m->from('alerta@micoopeguadalupana.com.gt','MicoopeGuadalupana');
                //AQUIEN LE LLEGA EL CORREO
                $m->to('dany.diaz@micoopeguadalupana.com.gt','Dany Diaz')->subject('Solicitudes de Pedidos');
            });
            $retorno = 1;
        }catch(Exception $e){
            DB::rollBack();
            $retorno = 0;
        }
        return ['value' => $retorno];
    }

    public function selectAgencia(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $agencia =  Agencia::select('id','nombre')
                    ->orWhere('nombre','like','%'.$filtro.'%')->get();
        //\Log::debug($agencia);
        return ['agencias' => $agencia];
    }

    public function selectDepartamento(Request $request){
        if (!$request->ajax()) return redirect('/');

        $idAgencia = $request->id;
        $agencia =  Agencia::Where('id','=',$idAgencia)->take(1)->get();

        $arrayDepartamento = array();
        foreach($agencia as $departamento){
            \Log::debug($departamento->Departamento);
            array_push($arrayDepartamento,$departamento->Departamento);
        }
        return ['departamento' => $agencia];

       /* $agencia =  Agencia::select('id','nombre')
            ->orWhere('nombre','like','%'.$filtro.'%')->get();
        \Log::debug($agencia);
        return ['agencias' => $agencia];*/
    }

    public function selectCategoria(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $categoria =  Categoria::select('id','nombre')
            ->orWhere('nombre','like','%'.$filtro.'%')->get();
        //\Log::debug($agencia);
        return ['categoria' => $categoria];
    }

    public function selectProducto(Request $request){
        if (!$request->ajax()) return redirect('/');

        $idCategoria = $request->id;
        $producto =  Producto::Where('idCategoria','=',$idCategoria)->get();

        return ['producto' => $producto];
    }

    public function getAllSolicitud(Request $request){

        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador','verificador']);

        /*$solicitud = DetalleSolicitud::join('solicituds','detalle_solicituds.idSolicitud','=','solicituds.id')
            ->join('users','solicituds.IdUser','=','users.id')
            ->join('productos','detalle_solicituds.IdProducto','=','productos.id')
            ->select('solicituds.id as soli_id','solicituds.fecha_hora','solicituds.nombre_solcitante','solicituds.comentario as com',
                'solicituds.status','users.email','productos.nombre','detalle_solicituds.cantidad','detalle_solicituds.comentario as comentario')
            ->get();*/

        $solicitud = Solicitud::select('id','nombre_solcitante','status','fecha_hora','num_orden')->get();


        return ['solicitud' => $solicitud];
    }

    public function getSolicitud(Request $request,$id){
        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador','verificador']);
        $solicitud = Solicitud::join('agencia_departamento','agencia_departamento.id','=','solicituds.idAge_depto')
            ->join('agencias','agencias.id','=','agencia_departamento.agencia_id')
            ->join('departamentos','departamentos.id','=','agencia_departamento.departamento_id')
            ->select('solicituds.id as id_Soli','solicituds.nombre_solcitante as nombre_soli','solicituds.fecha_hora','solicituds.status',
                'agencias.nombre as agencia_nombre','departamentos.nombre as departamento_nombre')
            ->where('solicituds.id','=',$id)
            ->first();

        $detalleSolicitud = Solicitud::join('detalle_solicituds','detalle_solicituds.idSolicitud','=','solicituds.id')
            ->join('productos','detalle_solicituds.IdProducto','=','productos.id')
            ->select('productos.nombre','detalle_solicituds.id as idDetalle','detalle_solicituds.cantidad','detalle_solicituds.comentario as comentario')
            ->where('solicituds.id','=',$id)
            ->get();
        return [
                 'detalleSolicitud' => $detalleSolicitud,
                 'solicitud' => $solicitud
                ];
    }

    public  function solicitudListo( Request $request,$id){
        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador','verificador']);
        $solicitud = Solicitud::find($id);
        $solicitud->status = 0;
        $solicitud->save();
    }

    public function pdf(Request $request, $id){

        $solicitud = Solicitud::join('agencia_departamento','agencia_departamento.id','=','solicituds.idAge_depto')
            ->join('agencias','agencias.id','=','agencia_departamento.agencia_id')
            ->join('departamentos','departamentos.id','=','agencia_departamento.departamento_id')
            ->select('solicituds.id as id_Soli','solicituds.num_orden as orden','solicituds.nombre_solcitante as nombre_soli','solicituds.fecha_hora','solicituds.status',
                'agencias.nombre as agencia_nombre','departamentos.nombre as departamento_nombre')
            ->where('solicituds.id','=',$id)
            ->first();

        $detalleSolicitud = Solicitud::join('detalle_solicituds','detalle_solicituds.idSolicitud','=','solicituds.id')
            ->join('productos','detalle_solicituds.IdProducto','=','productos.id')
            ->select('productos.nombre','detalle_solicituds.id as idDetalle','detalle_solicituds.cantidad','detalle_solicituds.comentario as comentario')
            ->where('solicituds.id','=',$id)
            ->get();

        $pdf = \PDF::loadView('pdf.detalle',['detalleSolicitud' => $detalleSolicitud,'solicitud' => $solicitud]);
        return $pdf->download('detalle.pdf');
    }
    /*
     *  juan.hernandez@micoopeguadalupana.com.gt solicitante
     *  verenize.prueba@micoopeguadalupana.com.gt	verificador
     *  yguzman@gmail.com	admin
     *
     */
}
