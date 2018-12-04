<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/main',function(){
    if(Auth::user()){

  if(Auth::user()->estado){
            return view('contenido.contenido');
        }else{
            //valida que el usuario que se loguea verifica si es un usuario activo si no lo desloguea
            Auth::logout();
            return redirect('/');
       }
    }
        return redirect('/');
})->name('main');
// <a href="{{ route('register') }}">Register</a>

Route::get('/user', 'UserController@index');
Route::post('/user/store', 'UserController@store');
Route::post('/user/actualizar', 'UserController@actualizar');
Route::post('/user/desactivar', 'UserController@desactivar');
Route::post('/user/activar', 'UserController@activar');
Route::get('/user/roles', 'UserController@userRoles');
Route::post('/user/updatePassword','UserController@updatePassword');

Route::get('/rol', 'RolController@index');
Route::post('/rol/store', 'RolController@store');
Route::post('/rol/actualizar', 'RolController@actualizar');
Route::post('/rol/desactivar', 'RolController@desactivar');
Route::post('/rol/activar', 'RolController@activar');
Route::get('/rol/roles', 'RolController@roles');

//ruta para subir los archivos
Route::post('/upLoad/store', 'CargasMasivasController@store');
Route::post('/upLoad/MostrarData', 'CargasMasivasController@MostrarData');
Route::get('/upLoad/ListAgencias', 'CargasMasivasController@ListAgencias');
Route::post('/upLoad/AddAgencias', 'CargasMasivasController@AddAgencias');
Route::get('/upLoad/senMail', 'CargasMasivasController@senMail');
Route::post('/upLoad/categoria', 'CargasMasivasController@categoria');
Route::post('/upLoad/producto', 'CargasMasivasController@producto');


//Route::resource('solicitud','SolicitudController');
Route::post('/solicitud/guardar','SolicitudController@guardar');
Route::get('/solicitud/selectAgencia','SolicitudController@selectAgencia');
Route::get('/solicitud/selectDepartamento','SolicitudController@selectDepartamento');
Route::get('/solicitud/selectCategoria','SolicitudController@selectCategoria');
Route::get('/solicitud/selectProducto','SolicitudController@selectProducto');
Route::get('/solicitud/getAllSolicitud','SolicitudController@getAllSolicitud');
Route::get('/solicitud/getSolicitud/{id}','SolicitudController@getSolicitud');
Route::post('/solicitud/solicitudListo','SolicitudController@solicitudListo');
Route::get('/solicitud/pdf/{id}','SolicitudController@pdf');
Route::get('/solicitud/productoBodega','SolicitudController@productoBodega');
Route::post('/solicitud/updateProductoBodega','SolicitudController@updateProductoBodega');
Route::get('/solicitud/getAllDetaelleSolicitud','SolicitudController@getAllDetaelleSolicitud');
Route::get('/solicitud/validarExitenciaProducto','SolicitudController@validarExitenciaProducto');
Route::get('/solicitud/getUser','SolicitudController@getUser');
Route::get('/solicitud/exportExecel','SolicitudController@exportExecel');
Route::post('/solicitud/solicitudRechazada','SolicitudController@solicitudRechazada');
Route::get('/solicitud/getAllSolicitudCliente','SolicitudController@getAllSolicitudCliente');
Route::post('/solicitud/compraProductos','SolicitudController@compraProductos');
Route::get('/solicitud/getAllCompra','SolicitudController@getAllCompra');



Route::get('/testing',function(){
    return view('TestFormulario.form');
})->name('testing');



