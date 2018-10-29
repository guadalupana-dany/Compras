<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;

class RolController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index(Request $request){
        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador']);
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $roles = Rol::select('id','nombre','descripcion','estado')
            ->orderBy('id','desc')->paginate(10);
        }else{
            $roles = Rol::select('id','nombre','descripcion','estado')
            ->where($criterio,'like', '%' . $buscar .'%')
            ->orderBy('id','desc')->paginate(10);
        }

        return [
            'pagination' =>[
                        "total"=> $roles->total(),
                        "current_page"=>  $roles->currentPage(),
                        "per_page"=> $roles->perPage(),
                        "last_page"=>  $roles->lastPage(),
                        "from"=>  $roles->firstItem(),
                        "to"=>  $roles->lastItem(),
                    ],

            'roles' =>$roles
        ];
    }

    public function roles(Request $request){
       if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador']);
        $roles = Rol::select('id','nombre')->get();
        return ['roles' => $roles];
    }

    public function store(Request $request){
        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador']);
        $rol = new Rol();
        $rol->nombre = $request->nombre;
        $rol->descripcion = $request->descripcion;
        $rol->estado = '1';
        $rol->save();
}

    public function actualizar(Request $request){
        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador']);
            $rol = Rol::find($request->id);
            $rol->nombre = $request->nombre;
            $rol->descripcion = $request->descripcion;
            $rol->estado = '1';
            $rol->update();
    }

    public function desactivar(Request $request){
        if (!$request->ajax()) return redirect('/');
         $request->user()->authorizeRoles(['Administrador']);
         $rol = Rol::find($request->id);
         $rol->estado = '0';
         $rol->save();
    }

    public function activar(Request $request){
        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador']);
        $rol = Rol::find($request->id);
        $rol->estado = '1';
        $rol->save();
   }
}
