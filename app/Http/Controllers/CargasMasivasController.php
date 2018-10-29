<?php

namespace App\Http\Controllers;

use App\Agencia;
use App\Categoria;
use App\Departamento;
use App\Producto;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Mockery\CountValidator\Exception;

class CargasMasivasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

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
                    if($accion == 'agencias'){
                        \Log::debug("agencias");
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
                  }
                \Log::debug($error);

                fclose($fh);
            }
        }else{
            \Log::debug("NO ES UN ARCHIVO");
        }

        if($error !=''){
            return ['error' => $error];
        }
        if($exito !=''){
            return ['exito' => $exito];
        }
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

}
