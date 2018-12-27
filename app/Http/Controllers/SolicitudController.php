<?php

namespace App\Http\Controllers;

use App\Agencia;
use App\User;
use App\Categoria;
use App\DetalleSolicitud;
use App\Producto;
use App\Solicitud;
use App\ControlBodega;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DetalleExport;


class SolicitudController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    //metodo que guarda una solicitud creada por el usuario
    public function guardar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');


        $retorno = 0;
        try{

            DB::beginTransaction();

            $agencia_departamento = DB::table('agencia_departamento')
                ->where('departamento_id','=',$request->idDepartamento)
                ->where('agencia_id','=',$request->Idagencia)
                ->first();

            /*foreach($agencia_departamento as $ad){
                //\Log::debug($ad->id);
                $idAge_depto = $ad->id;
            }*/

            $numOrd = DB::table('solicituds')->select('num_orden')
                ->orderby('created_at','DESC')->take(1)->get();


           if(count($numOrd) != 0){
                foreach($numOrd as $n){
                    $numero_orden = $n->num_orden + 1;
                }
           }else{
               $numero_orden = 1;
           }


            $mytime = Carbon::now('America/Guatemala');
            $solicitud = new Solicitud();
            $solicitud->fecha_hora = $mytime;
            $solicitud->nombre_solcitante = $request->nombre;
            $solicitud->idAge_depto = $agencia_departamento->id;
            $solicitud->comentario = $request->comentarioGeneral;
            $solicitud->idUser = $request->user()->id;
            $solicitud->num_orden = $numero_orden;
            $solicitud->save();

            $productos = $request->arrayDetalleListo;
            //\Log::debug($productos);
            foreach($productos as $ep=>$det){
                $detalleS =  new DetalleSolicitud();
                $detalleS->idSolicitud = $solicitud->id;
                $detalleS->cantidad = $det['cantidad'];
                $detalleS->idProducto = $det['idProducto'];
                $detalleS->comentario = $det['comentarioDetalle'];
                $detalleS->save();
            /*    if($det['idCategoria'] == 2){
                    $this->productoBodegaOne($detalleS->id, $det['idProducto']);
                }*/
                    $this->log('Inserto','Se inserto un Detalle de solicitud',$detalleS->id,$request->user()->id);
            }
                    $this->log('Inserto','Se inserto una solicitud',$solicitud->id,$request->user()->id);
            DB::commit();
            $this->envioCorreoSolicitudNew($numero_orden,$request);
            return ['value' => 1];
        }catch(Exception $e){
            DB::rollBack();
            return ['value' => 0];
        }

    }
    //guardarNuevaRechazada
    public function guardarNuevaRechazada(Request $request)
    {
        if (!$request->ajax()) return redirect('/');



        $retorno = 0;
        try{

            DB::beginTransaction();

           $numOrd = DB::table('solicituds')->select('num_orden')
                ->orderby('created_at','DESC')->take(1)->get();


           if(count($numOrd) != 0){
                foreach($numOrd as $n){
                    $numero_orden = $n->num_orden + 1;
                }
           }else{
               $numero_orden = 1;
           }


            $mytime = Carbon::now('America/Guatemala');

            $solicitud = new Solicitud();
            $solicitud->fecha_hora = $mytime;
            $solicitud->nombre_solcitante = $request->oneSolicitud['nombre_soli'];
            $solicitud->idAge_depto = $request->oneSolicitud['idAge_depto'];
            $solicitud->comentario = $request->oneSolicitud['comentarioGeneral'];
            $solicitud->idUser = $request->user()->id;
            $solicitud->num_orden = $numero_orden;
            $solicitud->save();

            $sol = Solicitud::find($request->oneSolicitud['id_Soli']);
            $sol->rechazo_retomado = 0;
            $sol->update();

            $productos = $request->detalleSolicitud;
            \Log::debug($productos);
            foreach($productos as $ep=>$det){
                $detalleS =  new DetalleSolicitud();
                $detalleS->idSolicitud = $solicitud->id;
                $detalleS->cantidad = $det['cantidad'];
                $detalleS->idProducto = $det['productoID'];
                $detalleS->comentario = $det['comentario'];
                $detalleS->save();
               /* if($det['idCategoria'] == 2){
                    $this->productoBodegaOne($detalleS->id, $det['productoID']);
                }*/
                    $this->log('Inserto','Se inserto un Detalle de solicitud',$detalleS->id,$request->user()->id);
            }
                    $this->log('Inserto','Se inserto una solicitud',$solicitud->id,$request->user()->id);
            DB::commit();
            $this->envioCorreoSolicitudNew($numero_orden,$request);
            return ['value' => 1];
        }catch(Exception $e){
            DB::rollBack();
            return ['value' => 0];
        }

    }
    //metodo que envia el correo cuando un usuario genera una solicitud
    public function envioCorreoSolicitudNew($numero_orden,$request){
            $users = $request->user();
        //SE CREA LA PLANTILLA Y [] DENTRO DE ESTO LE PASAMOS LOS PARAMETROS A LA PLANTILLA PARA QUE SEA DINAMICO
            Mail::send('emails.reminder',['users' => $users,'numero_orden' => $numero_orden],function($m) use ($users){
            //AQUIEN MANDA EL CORREO
            $m->from('alerta@micoopeguadalupana.com.gt','MicoopeGuadalupana');
            //AQUIEN LE LLEGA EL CORREO
          // $m->to('dany.diaz@micoopeguadalupana.com.gt','Dany Diaz')->subject('Nueva Requisición');
           $m->to('berenice.garcia@micoopeguadalupana.com.gt','Berenice Garcia')->cc('juliana.feliciano@micoopeguadalupana.com.gt','Juliana Feliciano')->subject('Nueva Requisición');

        });
    }
    //metodo que muestra solo un producto en bodega que recibe dos parametros el id del detale y un id de producto
    public function productoBodegaOne($detalleS_id,$idProducto){
        \Log::debug(" detalleS_id " . $detalleS_id . "idProducto" . $idProducto);

        $controlBodega = DB::table('control_bodega_')->Where('idProducto','=',$idProducto)->first();
        $detalle = DetalleSolicitud::find($detalleS_id);
        $detalle->precio_unitario = $controlBodega->total_unitario;
        $detalle->update();
         //   $control_bodega =
    }
    //metodo que selecciona la agencia por medio de la vista
    public function selectAgencia(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $agencia =  Agencia::select('id','nombre')
                    ->orWhere('nombre','like','%'.$filtro.'%')->get();
        //\Log::debug($agencia);
        return ['agencias' => $agencia];
    }
    //metodo que selecciona los departamentos que son buscados dinamicamente en la vista
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
    //metodo que selecciona la categoria que el usuario este buscando
    public function selectCategoria(Request $request){
        if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $categoria =  Categoria::select('id','nombre')
            ->orWhere('nombre','like','%'.$filtro.'%')->get();
        //\Log::debug($agencia);
        return ['categoria' => $categoria];
    }
    //metodo que valida la existencia de articulos en bodega
    public function validarExitenciaProducto(Request $request){
        if (!$request->ajax()) return redirect('/');
        \Log::debug($request);

        $idProducto = $request->idProducto;

        $controlBodega = DB::table('control_bodega_')
        ->select('total_stock')
        ->Where('idProducto','=',$idProducto)
        ->first();

        if($controlBodega){
            return ['result' => 1 ,'total_stock' => $controlBodega->total_stock];
        }else{
            return ['result' => 0];
        }


    }
    //metodo que selecciona el producto a buscar
    public function selectProducto(Request $request){
        if (!$request->ajax()) return redirect('/');

        $idCategoria = $request->id;
        $producto =  Producto::Where('idCategoria','=',$idCategoria)->get();

        return ['producto' => $producto];
    }
    //metodo que ve los productos en bodega
    public function productoBodega(Request $request){
        if (!$request->ajax()) return redirect('/');


        /*$productoB =  Producto::join('','','','')
        ->where('idCategoria','=','2')->get();*/
        $productoB =  ControlBodega::join('productos','control_bodega_.idProducto','=','productos.id')
        ->select('productos.id as idProducto','productos.nombre',
        'control_bodega_.id as idControl','control_bodega_.can_mes_anterior','control_bodega_.pre_u_mes_anterior',
        'control_bodega_.tot_mes_anterior','control_bodega_.can_mes_actual',
        'control_bodega_.pre_u_mes_actual','control_bodega_.tot_mes_actual','control_bodega_.total_stock',
        'control_bodega_.total_saldo','control_bodega_.total_unitario','control_bodega_.proveedor')
        ->where('productos.idCategoria','=','2')->get();

        return ['productoB' => $productoB];
    }
    //metodo que guarda en la bitacora las compras
    public function compraProductos(Request $request){
        if (!$request->ajax()) return redirect('/');
        $mytime = Carbon::now('America/Guatemala');

        DB::table('bitacora_compras')->insert(
            ['proveedor' => $request->proveedorC,
             'producto' =>  $request->productoC,
             'cantidad' => $request->cantidadC,
             'total_pagar' => $request->totalPagoC,
             'precio_unitario' => ($request->totalPagoC / $request->cantidadC),
             'fecha_compra' => $mytime,
             'idUser' => $request->user()->id
             ]
        );
    }
    public function getAllCompra(Request $request){
        if (!$request->ajax()) return redirect('/');
            $compras = DB::table('bitacora_compras')
                            ->join('users','bitacora_compras.idUser','=','users.id')
                            ->select('bitacora_compras.*','users.name')
                            ->get();
            return ['compras' => $compras];
    }
    //actualizar el stock en bodega
    public function updateProductoBodega(Request $request){
        if (!$request->ajax()) return redirect('/');
        $detalle = $request->data;
       //$det['cantidad'];
       $mytime = Carbon::now('America/Guatemala');
            foreach($detalle as $ep=>$det){

                $controlBodega = ControlBodega::find($det['idControl']);
                $controlBodega->can_mes_anterior = $det['total_stock'];
                $controlBodega->pre_u_mes_anterior = $det['total_unitario'];
                $controlBodega->tot_mes_anterior = $det['total_saldo'];
                $controlBodega->can_mes_actual = $request->cantidadUpdate;
                $controlBodega->pre_u_mes_actual = $request->precioUnitario;
                $controlBodega->tot_mes_actual = $request->total_pago;
                $controlBodega->total_stock =  $det['total_stock'] + $request->cantidadUpdate;
                $controlBodega->total_saldo =  $det['total_saldo'] + $request->total_pago;
                $controlBodega->total_unitario = ($det['total_saldo'] + $request->total_pago)/( $det['total_stock'] + $request->cantidadUpdate);
                $controlBodega->proveedor = $det['proveedor'];
                $controlBodega->update();

                DB::table('bitacora_compras')->insert(
                    ['proveedor' => $det['proveedor'],
                     'producto' =>  $det['nombre'],
                     'cantidad' => $request->cantidadUpdate,
                     'total_pagar' => $request->total_pago,
                     'precio_unitario' => $request->precioUnitario,
                     'fecha_compra' => $mytime,
                     'idUser' => $request->user()->id
                     ]
                );
        }

    }
        //metodo que muestra todas las solicitudes que se encuentra de cada cliente
    public function getAllSolicitudCliente(Request $request){

        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador','verificador','Departamento']);

        $solicitud = Solicitud::join('agencia_departamento','solicituds.idAge_depto','=','agencia_departamento.id')
        ->join('agencias','agencia_departamento.agencia_id','=','agencias.id')
        ->join('departamentos','agencia_departamento.departamento_id','=','departamentos.id')
        ->select('solicituds.id','solicituds.rechazo_retomado','solicituds.nombre_solcitante','solicituds.status','solicituds.total_gasto','solicituds.fecha_hora','solicituds.fecha_fin','solicituds.fecha_estimado','solicituds.num_orden','agencias.nombre as nombre_agencia','departamentos.nombre as nombre_Depto')
        ->where('solicituds.idUser','=',$request->user()->id)
        ->orderby('solicituds.fecha_hora','DESC')->get();


        return ['solicitud' => $solicitud];
    }
    //metodo que muestra todas las solicitudes que se encuentra
    public function getAllSolicitud(Request $request){

        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador','verificador','Departamento']);

        /*$solicitud = DetalleSolicitud::join('solicituds','detalle_solicituds.idSolicitud','=','solicituds.id')
            ->join('users','solicituds.IdUser','=','users.id')
            ->join('productos','detalle_solicituds.IdProducto','=','productos.id')
            ->select('solicituds.id as soli_id','solicituds.fecha_hora','solicituds.nombre_solcitante','solicituds.comentario as com',
                'solicituds.status','users.email','productos.nombre','detalle_solicituds.cantidad','detalle_solicituds.comentario as comentario')
            ->get();*/

        $solicitud = Solicitud::join('agencia_departamento','solicituds.idAge_depto','=','agencia_departamento.id')
        ->join('agencias','agencia_departamento.agencia_id','=','agencias.id')
        ->join('departamentos','agencia_departamento.departamento_id','=','departamentos.id')
        ->select('solicituds.id','solicituds.nombre_solcitante','solicituds.status','solicituds.total_gasto','solicituds.fecha_hora','solicituds.fecha_fin','solicituds.fecha_estimado','solicituds.num_orden','agencias.nombre as nombre_agencia','departamentos.nombre as nombre_Depto')->get();


        return ['solicitud' => $solicitud];
    }
    //metodo que muestra todos los detalles de las solicitudes
    public function getAllDetaelleSolicitud(Request $request){

            if (!$request->ajax()) return redirect('/');
                $request->user()->authorizeRoles(['Administrador','verificador']);
                //\Log::debug($request);

            $fechaInicio = $request->inicio;
            $fechaFin = $request->fin;
            $user = $request->user;
            $estado = $request->estado;
            $orden = $request->orden;

            $filterQuery = !empty($fechaInicio) && !empty($fechaFin) && !empty($user) && !empty($estado);

            $solicitud = DetalleSolicitud::join('productos','detalle_solicituds.IdProducto','=','productos.id')
            ->join('solicituds','detalle_solicituds.idSolicitud','=','solicituds.id')
            ->join('users','solicituds.IdUser','=','users.id')
            ->join('agencia_departamento','solicituds.idAge_depto','=','agencia_departamento.id')
            ->join('agencias','agencia_departamento.agencia_id','=','agencias.id')
            ->join('departamentos','agencia_departamento.departamento_id','=','departamentos.id')
            ->select('productos.nombre as nombre_producto','detalle_solicituds.precio_total','detalle_solicituds.cantidad','detalle_solicituds.comentario',
            'users.name as nombre_user','solicituds.fecha_hora as fecha','solicituds.num_orden','agencias.nombre as nombre_agencia',
            'departamentos.nombre as departamento_nombre','solicituds.status');

            if($fechaInicio and $fechaFin){
                    $solicitud
                    ->where('solicituds.fecha_hora','>=',$fechaInicio)
                    ->where('solicituds.fecha_hora','<=',$fechaFin);
                }

            if($estado != 3){
                    $solicitud->where('solicituds.status','=',$estado);
                }

            if($user){
                $solicitud->where('users.id','=',$user);
                }
                if($orden != 0){
                //  \Log::info($orden);
                    $solicitud->where('solicituds.num_orden','=',$orden);
                }


            $solicitud = $solicitud->paginate(10);
            return [
                'pagination' =>[
                            "total"=> $solicitud->total(),
                            "current_page"=>  $solicitud->currentPage(),
                            "per_page"=> $solicitud->perPage(),
                            "last_page"=>  $solicitud->lastPage(),
                            "from"=>  $solicitud->firstItem(),
                            "to"=>  $solicitud->lastItem(),
                        ],

                'solicitud' =>$solicitud
                ];

    }
    //metodo que exporta segun el usuario filtra
    public function exportExecel(Request $request){

            $fechaInicio = $request->inicio;
            $fechaFin = $request->fin;
            $user = $request->user;
            $estado = $request->estado;
            $orden = $request->orden;
            return Excel::download(new DetalleExport($fechaInicio,$fechaFin,$user,$estado,$orden), 'detalle.xlsx');

    }
    //metodo que obtiene la solicitudes
    public function getSolicitud(Request $request,$id){
        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador','verificador','Departamento']);
        $solicitud = Solicitud::join('agencia_departamento','agencia_departamento.id','=','solicituds.idAge_depto')
            ->join('agencias','agencias.id','=','agencia_departamento.agencia_id')
            ->join('departamentos','departamentos.id','=','agencia_departamento.departamento_id')
            ->select('solicituds.id as id_Soli','solicituds.nombre_solcitante as nombre_soli','solicituds.idAge_depto','solicituds.comentario as comentarioGeneral','solicituds.fecha_hora','solicituds.status',
                'agencias.nombre as agencia_nombre','departamentos.nombre as departamento_nombre')
            ->where('solicituds.id','=',$id)
            ->first();

        $detalleSolicitud = Solicitud::join('detalle_solicituds','detalle_solicituds.idSolicitud','=','solicituds.id')
            ->join('productos','detalle_solicituds.IdProducto','=','productos.id')
            ->select('productos.nombre','productos.idCategoria','detalle_solicituds.id as idDetalle','detalle_solicituds.comenRechazo','detalle_solicituds.cantidad','detalle_solicituds.comentario as comentario','detalle_solicituds.precio_unitario','productos.id as productoID')
            ->where('solicituds.id','=',$id)
            ->get();


        return [
                 'detalleSolicitud' => $detalleSolicitud,
                 'solicitud' => $solicitud
                ];
    }
    public function solicitudRechazada(Request $request){
        if (!$request->ajax()) return redirect('/');
       // \Log::debug($request);


        try{
            $fecha_fin1 = Carbon::now('America/Guatemala');
           // \Log::debug($fecha_fin1);
            DB::beginTransaction();
            $request->user()->authorizeRoles(['Administrador','Verificador']);
            $solicitud = Solicitud::find($request->idSolicitud);
            $solicitud->status = 2;
            $solicitud->fecha_fin = $fecha_fin1;
            $solicitud->update();

            $detSoli = $request->detalleSoli;
            foreach($detSoli as $ep=>$det){
                $detalleS =  DetalleSolicitud::find($det['idDetalle']);
                $detalleS->comenRechazo = $det['comenRechazo'];
                $detalleS->update();
                $this->log('Update','Se rechazo detalle de solicitud', $detalleS->id, $request->user()->id);

            }

            $users = User::find($solicitud->idUser);
        //SE CREA LA PLANTILLA Y [] DENTRO DE ESTO LE PASAMOS LOS PARAMETROS A LA PLANTILLA PARA QUE SEA DINAMICO
            Mail::send('emails.solicitudrechazado',['users' => $users,'numero_orden' => $solicitud->num_orden],function($m) use ($users){
            //AQUIEN MANDA EL CORREO
            $m->from('alerta@micoopeguadalupana.com.gt','MicoopeGuadalupana');

            $m->to($users->email,$users->name)->subject('Requisicion Rechazada');
          });

            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            \Log::debug('Exception  ' . $e);

        }
    }
    // metodo que realiza la solicitud lista cuando berenice ya haya atendido dicha solicitud
    public  function solicitudListo( Request $request){
      if (!$request->ajax()) return redirect('/');
      $mytime = Carbon::now('America/Guatemala');
      try{
        DB::beginTransaction();
        $request->user()->authorizeRoles(['Administrador','Verificador']);
        $solicitud = Solicitud::find($request->idSolicitud);
        $solicitud->status = 0;
        $solicitud->total_gasto = $request->total_gasto;
        $solicitud->fecha_fin = $mytime;
        $solicitud->fecha_estimado = $request->fecha_estimado;
        $solicitud->update();

        $detSoli = $request->detalleSoli;
        foreach($detSoli as $ep=>$det){
            $detalleS =  DetalleSolicitud::find($det['idDetalle']);
            $detalleS->cantidad = $det['cantidad'];
                if($det['idCategoria'] == 2){
                    $this->productoBodegaOne($detalleS->id, $det['productoID']);
                }else{
                    $detalleS->precio_unitario = $det['precio_unitario'];
                }
            $detalleS->precio_total = $det['cantidad'] * $det['precio_unitario'];
            $detalleS->comenRechazo = $det['comenRechazo'];
            $detalleS->update();


            $this->log('Update','Se despacho detalle de solicitud', $detalleS->id, $request->user()->id);
            $controlBodega = ControlBodega::where('idProducto','=', $det['productoID'])->first();
           if($controlBodega){
                $resta = $controlBodega->total_stock -  $det['cantidad'];
                $controlBodega->total_stock = $resta;
                $controlBodega->save();
            }
        }

        $this->log('Update','se despacho esta solicitud', $solicitud->id, $request->user()->id);
        DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            $retorno = 0;
        }

    }
    //function que exporta a pdf el detalle de la solicitud de cada solicitud
    public function pdf(Request $request, $id){

        $solicitud = Solicitud::join('agencia_departamento','agencia_departamento.id','=','solicituds.idAge_depto')
            ->join('agencias','agencias.id','=','agencia_departamento.agencia_id')
            ->join('departamentos','departamentos.id','=','agencia_departamento.departamento_id')
            ->select('solicituds.total_gasto as total','solicituds.id as id_Soli','solicituds.num_orden as orden','solicituds.nombre_solcitante as nombre_soli','solicituds.fecha_hora','solicituds.status',
                'agencias.nombre as agencia_nombre','departamentos.nombre as departamento_nombre')
            ->where('solicituds.id','=',$id)
            ->first();

        $detalleSolicitud = Solicitud::join('detalle_solicituds','detalle_solicituds.idSolicitud','=','solicituds.id')
            ->join('productos','detalle_solicituds.IdProducto','=','productos.id')
            ->select('detalle_solicituds.precio_total','productos.nombre','detalle_solicituds.id as idDetalle','detalle_solicituds.cantidad','detalle_solicituds.comentario as comentario')
            ->where('solicituds.id','=',$id)
            ->get();

        $pdf = \PDF::loadView('pdf.detalle',['detalleSolicitud' => $detalleSolicitud,'solicitud' => $solicitud]);
        return $pdf->download('detalle.pdf');
    }

    public function descargarStockPdf(){

        $productoB =  ControlBodega::join('productos','control_bodega_.idProducto','=','productos.id')
        ->select('productos.nombre','control_bodega_.total_stock')
        ->where('control_bodega_.total_stock','<','20')->get();

        $pdf = \PDF::loadView('pdf.controlStock',['productos' => $productoB]);
        return $pdf->download('stock.pdf');
    }
    //metodo que guarda las acciones del usuario
    private function log($tipo,$descripcion,$idObjeto,$idUser){
        $mytime = Carbon::now('America/Guatemala');
        DB::insert('insert into log (tipoAccion, descripcion, id_Objeto_Alterado, idUser, created_at) values (?, ?, ?, ?, ?)', [$tipo, $descripcion, $idObjeto, $idUser, $mytime]);
    }
    //metodo que devuelve todos los usuario
    public function getUser(Request $request){
      //  if (!$request->ajax()) return redirect('/');

        $filtro = $request->filtro;
        $users =  User::select('id','name')
                    ->orWhere('name','like','%'.$filtro.'%')->get();
        //\Log::debug($agencia);
        return ['users' => $users];
    }
    /*
     *  juan.hernandez@micoopeguadalupana.com.gt solicitante
     *  verenize.prueba@micoopeguadalupana.com.gt	verificador
     *  yguzman@gmail.com	admin
     *
     */
}
