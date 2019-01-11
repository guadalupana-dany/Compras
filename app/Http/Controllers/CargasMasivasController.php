<?php

namespace App\Http\Controllers;

use App\Agencia;
use App\Categoria;
use App\Departamento;
use App\Producto;
use App\User;
use App\ControlBodega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Mockery\CountValidator\Exception;
use App\Solicitud;
class CargasMasivasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    //metodo que guarda los archivos que son cargados en la vista masivos
    //TOdo esto solo el admin los puede ver
    public function store(Request $request){
        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador']);

        $exito = '';
        $error = '';
        if(Input::hasFile('archivo')){
            $masiva = Input::file('archivo');
            $accion = $request->Accion;
            $fh = fopen($masiva,"r");

            if($fh){
                  while(($row = fgetcsv($fh,0,";")) !== false){
                    if($accion == 'categoria'){
                       for($i = 0; $i < count($row); $i++){
                           try{
                              // DB::beginTransaction();
                               $categoria = new Categoria();
                               $categoria->nombre = $row[$i];
                               $categoria->save();
                               $exito = 'CATEGORIA INSERTADA CON EXITO';


                               //DB::commit();
                           }catch(\Illuminate\Database\QueryException $e){
                               //DB::rollBack();
                               if($e->getCode() == '23000'){
                                   $error .= ' Ya exite '.$row[$i].' en la BD';
                               }else{
                                   $error = ' Error al insertar ';
                               }
                           }

                       }
                    }
                    if($accion == 'producto'){
                       // \Log::debug("0 ".$row[0] ." 1 ".$row[1]);
                                try{
                                    // DB::beginTransaction();
                                    $producto = new Producto();
                                    $producto->idCategoria = utf8_encode($row[0]);
                                    $producto->nombre = utf8_encode($row[1]);
                                    $producto->save();
                                    $exito = 'PRODUCTO INSERTADO CON EXITO';
                                    //DB::commit();
                                }catch(\Illuminate\Database\QueryException $e){
                                    //DB::rollBack();
                                    if($e->getCode() == '23000'){
                                        $error .= ' YA HAY PRODUCTOS EXISTENTES EN LA BD';
                                    }else{
                                        $error = ' ERROR AL INSERTAR EN LA BD ';
                                    }
                                }

                    }
                    if($accion == 'producto_Bodega'){
                        // \Log::debug("0 ".$row[0] ." 1 ".$row[1]);
                                 try{
                                     // DB::beginTransaction();
                                     $producto = new Producto();
                                     $producto->idCategoria = utf8_encode($row[0]);
                                     $producto->nombre = utf8_encode($row[1]);
                                     $producto->save();
                                     $exito = 'PRODUCTO DE BODEGA INSERTADO CON EXITO';
                                     //DB::commit();
                                 }catch(\Illuminate\Database\QueryException $e){
                                     //DB::rollBack();
                                     if($e->getCode() == '23000'){
                                         $error .= ' YA HAY PRODUCTOS EXISTENTES EN LA BD';
                                     }else{
                                         $error = ' ERROR AL INSERTAR EN LA BD ';
                                     }
                                 }

                    }
                    if($accion == 'agencias'){

                        for($i = 0; $i < count($row); $i++){
                            try{
                                // DB::beginTransaction();
                                $agencia = new Agencia();
                                $agencia->nombre = $row[$i];
                                $agencia->save();
                                $exito = 'AGENCIA INSERTADA CON EXITO';
                                //DB::commit();
                            }catch(\Illuminate\Database\QueryException $e){
                                //DB::rollBack();
                                if($e->getCode() == '23000'){
                                    $error .= ' Ya exite '.$row[$i].' en la BD';
                                }else{
                                    $error = ' Error al insertar ';
                                }
                            }

                        }
                    }
                    if($accion == 'stock_Bodega'){

                                 try{
                                      DB::beginTransaction();
                                      /*  $producto = new Producto();
                                        $producto->idCategoria = utf8_encode($row[0]);
                                        $producto->nombre = utf8_encode($row[1]);
                                        $producto->save();
                                      */

                                        $control = new ControlBodega();
                                        $control->idProducto = $row[0];
                                        $control->can_mes_anterior = $row[1];
                                        $control->pre_u_mes_anterior = $row[2];
                                        $control->tot_mes_anterior = $row[3];
                                        $control->can_mes_actual = $row[4];
                                        $control->pre_u_mes_actual = $row[5];
                                        $control->tot_mes_actual = $row[6];
                                        $control->total_stock = $row[7];
                                        $control->total_saldo = $row[8];
                                        $control->total_unitario = $row[9];
                                        $control->save();
                                        $exito = 'PRODUCTO Y CONTROL DE BODEGA FUE INSERTADO CON EXITO';

                                        DB::commit();
                                 }catch(\Illuminate\Database\QueryException $e){
                                     DB::rollBack();
                                     if($e->getCode() == '23000'){
                                         $error .= ' YA HAY PRODUCTOS EXISTENTES EN LA BD';
                                     }else{
                                         $error = ' ERROR AL INSERTAR EN LA BD ' . $e;
                                     }
                                 }

                    }
                  }

                fclose($fh);
            }
        }else{
            \Log::debug("NO ES UN ARCHIVO");
        }

        if($error !=''){
            return ['error' => $error];
        }
        if($exito !=''){
            $this->log('Create','Subio un archivo llamado ' . $accion,0,$request->user()->id);
            return ['exito' => $exito];
        }
    }

    public function categoria(Request $request){
      /*  $categoria = new Categoria();
        $categoria->nombre = $request->descCategoria;
        $categoria->save();*/

        $detallesoli = Solicitud::where('precio_total','=','null')->get();
        \Log::debug('vacios');
        \Log::debug($detallesoli);
    }
    public function producto(Request $request){
        $producto = new Producto();
        $producto->idCategoria = $request->IdCategoria;
        $producto->nombre = $request->descProducto;
        $producto->save();
    }

    public function MostrarData(Request $request){
        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador']);

        //CATEGORI
        if($request->cataSelec == 0){
                $categoria = Categoria::select('id','nombre')->orderBy('id','asc')->paginate(15);
            return[
                'pagination' =>[
                    "total"=> $categoria->total(),
                    "current_page"=>  $categoria->currentPage(),
                    "per_page"=> $categoria->perPage(),
                    "last_page"=>  $categoria->lastPage(),
                    "from"=>  $categoria->firstItem(),
                    "to"=>  $categoria->lastItem(),
                ],
            'categoria' =>$categoria
            ];

        }
        //PRODUCTO
        if($request->cataSelec == 1){
            $producto = Producto::join('categorias','productos.idCategoria','=','categorias.id')
            ->select('productos.id','productos.nombre','productos.idCategoria','categorias.nombre as nombre_cat')
            ->orderBy('nombre','asc')->paginate(15);
            return[
                'pagination' =>[
                    "total"=> $producto->total(),
                    "current_page"=>  $producto->currentPage(),
                    "per_page"=> $producto->perPage(),
                    "last_page"=>  $producto->lastPage(),
                    "from"=>  $producto->firstItem(),
                    "to"=>  $producto->lastItem(),
                ],
                'producto' =>$producto
            ];
        }
        //AGENCIAS
        if($request->cataSelec == 2){
            $agencia = Agencia::select('id','nombre')->orderBy('id','asc')->paginate(15);
            return[
                'pagination' =>[
                    "total"=> $agencia->total(),
                    "current_page"=>  $agencia->currentPage(),
                    "per_page"=> $agencia->perPage(),
                    "last_page"=>  $agencia->lastPage(),
                    "from"=>  $agencia->firstItem(),
                    "to"=>  $agencia->lastItem(),
                ],
                'agencia' =>$agencia
            ];
        }
        //DEPARTAMENTOS
        if($request->cataSelec == 3){
            $departamento = Departamento::select('id','nombre')->orderBy('id','asc')->paginate(15);

            $arrayAgencias = array();
            foreach($departamento as $agencia){
                \Log::debug($agencia->Agencia);
                array_push($arrayAgencias,$agencia->Agencia);
            }

            return[
                'pagination' =>[
                    "total"=> $departamento->total(),
                    "current_page"=>  $departamento->currentPage(),
                    "per_page"=> $departamento->perPage(),
                    "last_page"=>  $departamento->lastPage(),
                    "from"=>  $departamento->firstItem(),
                    "to"=>  $departamento->lastItem(),
                ],
                'departamento' =>$departamento
            ];
        }

           //\Log::debug($request);


    }

    public function ListAgencias(Request $request){
        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador']);
        $agencias = Agencia::select('id','nombre')->get();
        return ['agencia' => $agencias];

    }
    //metodo que agrega departamentos a las agencias
    public function AddAgencias(Request $request){
        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador']);
        $exito = '';
        $error = '';
        try{
            $departamento =  new Departamento();
            $departamento->nombre = $request->departamento;
            $departamento->save();
            $departamento->Agencia()->attach($request->arrayAgenciasSeleccionada);
            $exito .='DEPARTAMENTO AGREGADO CON EXITO';
            $this->log('Create','Creo un departamento de Agencias',$departamento->id,$request->user()->id);
        }catch(\Illuminate\Database\QueryException $e){
            //DB::rollBack();
            if($e->getCode() == '23000'){
                $error .= ' YA HAY DEPARTAMENTO EXISTENTES EN LA BD';
            }else{
                $error = ' ERROR AL INSERTAR EN LA BD ';
            }
        }
        if($error !=''){
            return ['error' => $error];
        }
        if($exito !=''){
            return ['exito' => $exito];
        }


    }

    //funcion que manda correo cuando se crea un pedido
    public function senMail(){

        $user = User::findOrFail(1);
        //SE CREA LA PLANTILLA Y [] DENTRO DE ESTO LE PASAMOS LOS PARAMETROS A LA PLANTILLA PARA QUE SEA DINAMICO
        Mail::send('emails.reminder',['user' => $user],function($m) use ($user){
            //AQUIEN MANDA EL CORREO
            $m->from('alerta@micoopeguadalupana.com.gt','MicoopeGuadalupana');
            //AQUIEN LE LLEGA EL CORREO
            $m->to('oscar.orosco@micoopeguadalupana.com.gt','Oscar Orosco')->subject('Pedidos');
        });
    }

    //metodo que guarda las acciones que realiza el usuario
    private function log($tipo,$descripcion,$idObjeto,$idUser){
        $mytime = Carbon::now('America/Guatemala');
        DB::insert('insert into log (tipoAccion, descripcion, id_Objeto_Alterado, idUser, created_at) values (?, ?, ?, ?, ?)', [$tipo, $descripcion, $idObjeto, $idUser, $mytime]);
   }

}
