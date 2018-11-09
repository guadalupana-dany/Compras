<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
            </ol>
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>Solicitudes Recibidas
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label for=""><b>Inicio:</b></label>
                                <input type="date" v-model="fechaInicio" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label for=""><b>Fin:</b></label>
                               <input type="date" v-model="fechaFin" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label for=""><b>Nombre:</b></label>
                                 <vSelect
                                    :on-search="getUsers"
                                    label="name"
                                    :options="ArrayUsers"
                                    placeholder ="Buscar Usuario ..."
                                    :onChange="getDatosUsers">
                                </vSelect>
                            </div>
                            <div class="col-md-2">
                                <label for=""><b>Estado:</b></label>
                                <select class="form-control" v-model="estado">
                                    <option value="2">Todos</option>
                                    <option value="1">Prendiente</option>
                                    <option value="0">Realizado</option>
                                </select>
                            </div>
                             <div class="col-md-2">
                                <label for=""><b>#Orden:</b></label>
                                <input type="number" v-model="orden" class="form-control">
                            </div>
                            <div class="col-md-2">
                                    <div class="form-group">
                                         <label for=""><b>Buscar:</b></label>
                                         <button @click="getDetalle(1)" class="btn btn-info form-control"><i class="fa  fa-search-plus"></i></button>
                                    </div>
                            </div>
                        </div>
                            <div class="form-group row">
                                <div class="col-md-2">
                                        <div class="form-group">
                                            <label for=""><b>Exportar Excel:</b></label>
                                            <button @click="exportExcel()" class="btn btn-success form-control"><i class="fa fa-file-excel-o"></i></button>
                                        </div>
                                </div>
                            </div>
                        <hr>
                        <div class="table-responsive">
                       <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th># Orden</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Pago</th>
                                    <th>Comentario</th>
                                    <th>Recibido</th>
                                    <th>fecha</th>
                                    <th>Agencia</th>
                                    <th>Departamento</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(sol,index) in detalleSolicitud">
                                <tr >
                                    <td v-text="index"></td>
                                    <td v-text="sol.num_orden"></td>
                                    <td v-text="sol.nombre_producto"></td>
                                    <td v-text="sol.cantidad"></td>
                                    <td v-text="sol.precio_total"></td>
                                    <td v-text="sol.comentario"></td>
                                    <td v-text="sol.nombre_user"></td>
                                    <td v-text="sol.fecha"></td>
                                    <td v-text="sol.nombre_agencia"></td>
                                    <td v-text="sol.departamento_nombre"></td>
                                    <td>
                                        <template v-if="sol.status"><span class="badge badge-warning">Pendiente</span></template>
                                        <template v-else><span class="badge badge-info">Realizado</span></template>
                                    </td>
                                </tr>
                                </template>
                            </tbody>
                         </table>
                         <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginacion(pagination.current_page - 1)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active': '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginacion(page)" v-text="page"></a>
                                </li>
                                <li class="page-item"  v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginacion(pagination.current_page + 1)" >Sig</a>
                                </li>
                            </ul>
                        </nav>
                         </div>

                    </div>
                </div>

            </div>
        </main>
</template>


<script>
    import VueLoading from 'vue-loading-overlay';
    import Datepicker from 'vuejs-datepicker';

    import 'vue-loading-overlay/dist/vue-loading.min.css';
    import swalPlugin from '../../../../public/js/VueSweetalert2.js';
     import vSelect from 'vue-select'
    Vue.use(swalPlugin);
    Vue.use(VueLoading);
    let $ = jQuery;
    export default {
        props : ['ruta'],
        data(){
            return{
                //var que llena los detalles de la solicitud
                detalleSolicitud : [],
                modal : 0,
                //variables para filtrar
                fechaFin : '',
                fechaInicio: '',
                estado : 2,
                ArrayUsers : [],
                idUsuario : 0,
                orden : 0,

                pagination : {
                        'total' : 0,
                        'current_page' : 0,
                        'per_page' : 0,
                        'last_page' : 0,
                        'from' : 0,
                        'to' : 0,
                },
                offset : 3,
                }

        },
        computed:{
            isActived: function(){
                    return this.pagination.current_page;
                },
                pagesNumber: function(){
                    if(!this.pagination.to){
                        return [];
                    }

                    var from = this.pagination.current_page -this.offset;
                    if(from < 1){
                        from = 1;
                    }

                    var to = from + (this.offset * 2);
                    if(to >= this.pagination.last_page){
                        to = this.pagination.last_page;
                    }

                    var pagesArray = [];
                    while(from <= to){
                        pagesArray.push(from);
                        from++;
                    }

                    return pagesArray;
                }
        },
        components: {
            Loading: VueLoading,
            Datepicker,
            vSelect
        },
        methods : {
            //metodo para el spinner d espera
            open() {

                let loader = this.$loading.show();
                setTimeout(() => loader.hide(),1000);
            },
            show() {
                this.visible = true
            },
            //metodo para la paginacion
            cambiarPaginacion(page){
                    //metodo que cambia de pagina
                    let me = this;
                    me.pagination.current_page = page;
                    me.getDetalle(page,0);
            },
            //metodo que nos muestra todos los detalle
            getDetalle(page){
                    let me = this;
                    let url = me.ruta + '/solicitud/getAllDetaelleSolicitud?page=' + page + '&inicio=' + me.fechaInicio + '&fin=' + me.fechaFin + '&user=' + me.idUsuario + '&estado=' +me.estado + '&orden=' +me.orden;

                    axios.get(url).then(function(response){

                            var respuesta = response.data;
                            me.detalleSolicitud = respuesta.solicitud.data;
                            me.pagination = respuesta.pagination;
                          //  me.dataTable();
                    })
                    .catch(function (error){
                            console.log(error);
                    });
            },
            //metodo que nos exporta a excel los filtros que tengamos
            exportExcel(){
                let me = this;
                let url = me.ruta + '/solicitud/exportExecel?inicio=' + me.fechaInicio + '&fin=' + me.fechaFin + '&user=' + me.idUsuario + '&estado=' +me.estado + '&orden=' +me.orden;
                window.open(url,'_blank');
            },
            //metodo que nos da todos los usuarios
            getUsers(search,loading){
                let me = this;
                loading(true);
                var url = this.ruta + '/solicitud/getUser?filtro='+search;
                axios.get(url).then(function(response){
                    q: search
                    me.ArrayUsers = response.data.users;
                    loading(false)
                }).catch(function(error){
                    console.log("Error en listAgencia");
                });
            },
            //metodo que nos da los dato del usuario
            getDatosUsers(val1){
                let me = this;
                me.loading = true;
                if(val1 !== null){
                    me.idUsuario = val1.id;
                }else{
                    me.idUsuario = 0;
                }
            },
        },
        mounted() {
            let me = this;
            this.open();
            this.getDetalle(1);
           // this.dataTable();

        },
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
