<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
            </ol>
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Ingresando Solicitud
                    </div>
                    <div class="card-body">
                            <div class="box box-info">
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label for="inputEmail3" >Nombre:</label>
                                                <input type="text" class="form-control" v-model="nombre" placeholder="Ingrese su nombre">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="inputEmail3" class="control-label">Agencia:</label>
                                                <vSelect
                                                    :on-search="selectAgencia"
                                                    label="nombre"
                                                    :options="ArrayAgencia"
                                                    placeholder ="Buscar Agencias ..."
                                                    :onChange="getDatosAgencia">
                                                </vSelect>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="inputEmail3" >Departamento:</label>
                                                <select class="form-control" v-model="idDepartamento">
                                                  <option v-for="depto in ArrayDepartamento" :key="depto.id" v-bind:value="depto.id" v-text="depto.nombre"></option>
                                                </select>
                                            </div>
                                        </div>
                                        <hr>
                                        <h4>Solicitud</h4>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label for="inputEmail3" class="control-label">Categoria:</label>
                                                <vSelect
                                                    :on-search="selectCategoria"
                                                    label="nombre"
                                                    :options="ArrayCategoria"
                                                    placeholder ="Buscar categoria ..."
                                                    :onChange="getDatosCategoria">
                                                </vSelect>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="inputEmail3" >Producto:</label>
                                                <select class="form-control" id="selectProducto">
                                                    <option v-for="producto in ArrayProducto" :key="producto.id" v-bind:value="producto.id" v-text="producto.nombre" ></option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="inputEmail3" >Cantidad:</label>
                                                <input type="number" class="form-control" id="cantidadProducto"  placeholder="Ingrese Cant ..">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="inputEmail3" >Comentario:</label>
                                                <input type="text" class="form-control" v-model="comentarioDetalle"  placeholder="Ingrese comentario para este producto ..">
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top:25px">
                                            <div class="col-md-2">
                                                <button class="btn btn-success" @click="agregarDetalle()">Agregar Producto +</button>
                                            </div>
                                        </div>
                                        <div v-show="errorDetalle" class="form-group">
                                            <div class="text-center text-error">
                                                <div v-for="error in arrayDetalle" :key="error" v-text="error">

                                                </div>
                                            </div>
                                        </div>
                                        <div  style="margin-top:25px">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                <th style="width: 10px">#</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Comentario</th>
                                                <th>Eliminar</th>
                                                </tr>
                                                </thead>
                                                <tbody v-if="arrayDetalleListo.length">
                                                    <tr v-for="(detalle,index) in arrayDetalleListo" :key="detalle.id">
                                                        <td v-text="index+1"></td>
                                                        <td v-text="detalle.nombreProducto"></td>
                                                        <td ><input type="number" class="form-control" v-model="detalle.cantidad"></td>
                                                        <td v-text="detalle.comentarioDetalle"></td>
                                                        <td><button class="btn btn-danger btn-sm" @click="eliminarDetalle(index)"><i class="icon-close"></i></button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="row" style="margin-top:25px">
                                            <div class="col-md-12">
                                                    <div class="form-group">
                                                    <label>Comentarios</label>
                                                    <textarea class="form-control" v-model="comentarioGeneral" rows="3" placeholder="Comentario ..."></textarea>
                                                    </div>
                                            </div>
                                        </div>
                                         <div class="row" style="margin-top:25px">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <template v-if="btnEnviar==0">
                                                        <button class="btn btn-info btn-lg" @click="enviarSolicitud">
                                                            E N V I A R --  S O L I C I T U D
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </main>
</template>


<script>
 import swalPlugin from '../../../../public/js/VueSweetalert2.js';
 Vue.use(swalPlugin);
 import vSelect from 'vue-select'

    export default {
        props : ['ruta'],
        data(){
            return{
                //espiner
                visible: false,
                arrayUsuario : [],

               //variables para la paginacion y la busqueda
                buscar : '',
                criterio : 'name',
                offset : 3,
                pagination : {
                        'total' : 0,
                        'current_page' : 0,
                        'per_page' : 0,
                        'last_page' : 0,
                        'from' : 0,
                        'to' : 0,
                         },
                //datos de la solicitud
                fechaActual : new Date().getDate() +'/'+new Date().getMonth()+'/'+new Date().getYear(),
                nombre : '',
                comentarioGeneral :'',

                //variable para la selecciones dinamicas
                Idagencia : 0,
                idDepartamento : 0,
                IdCategoria : 0,

                //variables para el detalle de la solicitud
                IdProducto : 0,
                comentarioDetalle : '',
                cantidad : 0,
                nombreProducto : '',

                //buscando Agencia
                ArrayAgencia : [],
                ArrayDepartamento : [],

                //buscando Categoria
                ArrayCategoria : [],
                ArrayProducto : [],

                //Errores que valida el agregar al detalle y el array detalle
                errorDetalle : 0,
                arrayDetalle : [],
                arrayDetalleListo : [],

                btnEnviar : 0,
                }

        },
        components: {
            vSelect
         // Loading: VueLoading
    },
        methods :{

            //metodo que busca las agencias
            selectAgencia(search,loading){
                let me = this;
                loading(true);
                var url = this.ruta + '/solicitud/selectAgencia?filtro='+search;
                axios.get(url).then(function(response){
                    q: search
                    me.ArrayAgencia = response.data.agencias;
                    loading(false)
                }).catch(function(error){
                    console.log("Error en listAgencia");
                });

            },
            //metodo que obtiene la agencia seleccionada
            getDatosAgencia(val1){
                let me = this;
                me.loading = true;
                if(val1 !== null){
                    me.Idagencia = val1.id;//almacenamos el id
                    var url = this.ruta + '/solicitud/selectDepartamento?id='+me.Idagencia;
                    axios.get(url).then(function(response){
                        let array = response.data.departamento;

                        for(var i= 0; i < array.length;i++){
                            me.ArrayDepartamento = array[i].departamento
                        }

                       // console.log(me.ArrayDepartamento.departamento);
                    }).catch(function(error){
                        console.log("Error en listAgencia");
                    });
                }else{
                    me.ArrayDepartamento = [];
                    me.ArrayAgencia = [];
                }
            },
            //metodo que busca la categoria
            selectCategoria(search,loading){
                let me = this;
                loading(true);
                var url = this.ruta + '/solicitud/selectCategoria?filtro='+search;
                axios.get(url).then(function(response){
                    q: search
                    me.ArrayCategoria = response.data.categoria;
                    loading(false)
                }).catch(function(error){
                    console.log("Error en listCategoria");
                });

            },
            //metodo que obtiene los datos de la categoria seleccionada
            getDatosCategoria(val1){
                let me = this;
                me.loading = true;
                if(val1 !== null){
                    me.IdCategoria = val1.id;//almacenamos el id
                    var url = this.ruta + '/solicitud/selectProducto?id='+me.IdCategoria;
                    axios.get(url).then(function(response){
                           me.ArrayProducto = response.data.producto;
                    }).catch(function(error){
                        console.log("Error en listAgencia");
                    });
                }else{
                    me.ArrayProducto = [];
                    me.ArrayCategoria = [];
                }
            },
            //metodo que busca en el array del detalle si ya exite que ya no lo vuelba a ingresar
            encuentra(id){
                //funcion que encuetra el articulo exites en el array

                var sw=false;
                for(var i=0;i<this.arrayDetalleListo.length; i++){
                    if(this.arrayDetalleListo[i].idProducto == id){
                        sw = true;
                    }
                }
                return sw;
            },
            //metedo que agrega al detall
            agregarDetalle(){
                let me = this;

                if(this.validatDetalle()){
                    return;
                }
                    var seleccion = document.getElementById('selectProducto');
                    me.IdProducto = seleccion.options[seleccion.selectedIndex].value;
                    me.nombreProducto = seleccion.options[seleccion.selectedIndex].text;
                    me.cantidad = document.getElementById('cantidadProducto').value;
                if(me.encuentra(me.IdProducto)){
                    me.$swal({
                        type: 'error',
                        title: 'Error...',
                        text: 'Tu articulo ya esta seleccionado!'
                    })
                }else
                {
                            me.arrayDetalleListo.push({
                                idCategoria : me.IdCategoria,
                                idProducto: me.IdProducto,
                                nombreProducto: me.nombreProducto,
                                cantidad: me.cantidad,
                                comentarioDetalle: me.comentarioDetalle
                            });

                            document.getElementById("cantidadProducto").value = "";

                            me.nombreProducto = '';
                            //me.cantidad = 0;
                            me.comentarioDetalle = '';


                   // me.ArrayCategoria = [];
                   // me.ArrayProducto = [];
                }
            },
            //metodo que elimina del detall
            eliminarDetalle(index){
                let me=this;
                me.arrayDetalleListo.splice(index,1);
            },
            //metdo que valida el detalle que no este vacio
            validatDetalle(){
                let me = this;
                me.errorDetalle = 0;
                me.arrayDetalle = [];
                me.cantidad = document.getElementById('cantidadProducto').value;
                if(!me.ArrayProducto.length) me.arrayDetalle.push("Ingrese Alguna categoria y producto");
                if(!me.cantidad) me.arrayDetalle.push("Ingrese Cantidad");
                if(!me.comentarioDetalle) me.arrayDetalle.push("Ingrese Comentario al Detalle");
                if(me.arrayDetalle.length) me.errorDetalle = 1;
                return me.errorDetalle;
            },
            //metodo que envia la solicitud
            enviarSolicitud () {

                let me = this;
                if(me.validarEnvio()){
                    me.$swal({
                        type: 'error',
                        title: 'Campos Obligatorios ...',
                        text: 'Nombre Completo, Agencia y Departamento, Productos, Comentario Final'
                    })
                    return;
                }
                                me.$swal({
                                            title: 'Enviar Solicitud?',
                                            text: 'Revisar tu solicitud antes de enviar',
                                            type: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Si Enviar!',
                                            cancelButtonText: 'No, Cancelar!',
                                            showCloseButton: true,
                                            showLoaderOnConfirm: true
                                            }).then((result) => {
                            if(result.value) {
                                   me.btnEnviar = 1;
                                    var url = me.ruta + '/solicitud/guardar';
                                    axios.post(url,{
                                        'nombre' : me.nombre,
                                        'idDepartamento' : me.idDepartamento,
                                        'Idagencia' : me.Idagencia,
                                        'arrayDetalleListo' :  me.arrayDetalleListo,
                                        'comentarioGeneral' :  me.comentarioGeneral
                                    }).then(function(response) {

                                            if(response.data.value == 1){
                                                me.$swal({
                                                    type: 'success',
                                                    title: 'Enviado...',
                                                    text: 'Tu solicitud a sido enviada!'
                                                })
                                            }else{
                                                me.$swal({
                                                    type: 'error',
                                                    title: 'Error...',
                                                    text: 'Ocurrio un error al enviar la solicitud!'
                                                })
                                            }
                                            me.limpiarCampos();

                                    }).catch(function(error){
                                        console.log("Error en listAgencia");
                                    });

                            } else {
                                this.$swal('Cancelado', 'Cancelaste el envio de la Solicitud', 'info')
                            }
                    })
                //solicitud


            },
            //metodo que valida el envio antes de que el usuario le de enviar
            validarEnvio(){
                var error = 0;
                var me = this;
                var stringError = '';

                if(!me.nombre) stringError += ' * Nombre Completo ';
                if(!me.idDepartamento) stringError += ' * Deparatamento ';
                if(!me.Idagencia) stringError += ' * Agencia ';
                if(!me.arrayDetalleListo.length) stringError += ' * Productos ';
                if(!me.comentarioGeneral) stringError += ' * Comentario Final ';

                if(stringError != ''){
                    error = 1;
                }
                return error;

            },
            limpiarCampos(){
                var me = this;
                me.nombre = '';
                me.idDepartamento = 0;
                me.Idagencia = 0;
                me.arrayDetalleListo = [];
                me.comentarioGeneral = [];
                me.ArrayAgencia = [];
                me.arrayDetalleListo = [];
                me.IdProducto = 0;
                me.IdCategoria = 0;
                me.ArrayCategoria = [];
                me.ArrayProducto = [];
                me.ArrayDepartamento = [];
                let url = me.ruta + '/logout';
                axios.post(url).then(function(response) {
                        location.href = 'http://10.60.81.12:81/sisPlanilla/public/';
                }).catch(function(error){

                });
               // location.href = 'http://10.60.81.12:81/sisPlanilla/public/logout';
                //window.open('http://10.60.81.12:81/sisPlanilla/public/logout');
            }
        },
        mounted() {

        }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>
