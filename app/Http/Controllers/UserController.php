<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
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
            $user = User::select('id','name','email','password','estado')
            ->orderBy('id','desc')->paginate(10);;
        }else{
            $user = User::select('id','name','email','password','estado')
            ->where($criterio,'like', '%' . $buscar .'%')
            ->orderBy('id','desc')->paginate(10);
        }
        //no se como fregados saca los roles pero los saco aqui

             $arrayRoles = array();
             foreach($user as $rol){
                array_push($arrayRoles,$rol->roles);
            }


        return [
                'pagination' =>[
                "total"=> $user->total(),
                "current_page"=>  $user->currentPage(),
                "per_page"=> $user->perPage(),
                "last_page"=>  $user->lastPage(),
                "from"=>  $user->firstItem(),
                "to"=>  $user->lastItem(),
            ],
            'user' =>$user,

        ];
    }

    public function userRoles(Request $request){

        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador']);
        $user = User::find($request->id);
        $roles = $user->roles;
        return ['rolesUser' => $roles,'user' => $user];
    }

    public function updatePassword(Request $request){
              $request->user()->authorizeRoles(['Administrador']);
              $user = User::find($request->id);
              $user->password = bcrypt($request->password);
              $user->update();
    }

    public function store(Request $request){
        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador']);
        try{
            DB::beginTransaction();
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->estado = '1';
            $user->save();
            $user->roles()->attach($request->data);
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }

    }

    public function actualizar(Request $request){
        if (!$request->ajax()) return redirect('/');
        $request->user()->authorizeRoles(['Administrador']);
         try{
            DB::beginTransaction();
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->estado = '1';
        $user->update();
        $user->roles()->sync($request->data);
        DB::commit();
        }catch(Exception $e){
            DB::rollBack();
        }
    }

    public function desactivar(Request $request){
        $request->user()->authorizeRoles(['Administrador']);
         $user = User::find($request->id);
         $user->estado = '0';
         $user->save();
    }

    public function activar(Request $request){
        $request->user()->authorizeRoles(['Administrador']);
        $user = User::find($request->id);
        $user->estado = '1';
        $user->save();
   }
}
