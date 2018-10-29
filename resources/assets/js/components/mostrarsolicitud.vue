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
                       <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Opciones</th>
                                    <th>No. Orden</th>
                                    <th>Solicitante</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(sol,index) in solicitud">
                                    <template v-if="sol.status">
                                            <tr >
                                                <td v-text="index"></td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="ver detalle de la solicitud" @click="verSolicitud(sol.id)" >
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <template v-if="sol.status">
                                                        <button type="button" class="btn btn-info btn-sm"  data-toggle="tooltip" title="Realizado" @click="realizado(sol.id)" >
                                                            <i class="icon-check"></i>
                                                        </button>
                                                    </template>
                                                    <button type="button" class="btn btn-danger btn-sm"  data-toggle="tooltip" title="Exportar Pdf" @click="descargarPdf(sol.id)">
                                                       <i class="fa fa-file-pdf-o"></i>
                                                    </button>
                                                </td>
                                                <td v-text="sol.num_orden"></td>
                                                <td v-text="sol.nombre_solcitante"></td>
                                                <td>
                                                    <template v-if="sol.status"><span class="badge badge-warning">Pendiente</span></template>
                                                    <template v-else><span class="badge badge-info">Realizado</span></template>
                                                </td>
                                            </tr>
                                    </template>
                                </template>
                            </tbody>
                         </table>

                    </div>
                </div>

            </div>

            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Detalle de Solicitud</h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="card">
                                    <div class="card-header">
                                        <i class="fa fa-align-justify"></i>Detalle de la Solicitud
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4"><b>Nombre Completo:</b></div>
                                            <div class="col-sm-6" v-text="oneSolicitud.nombre_soli"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4"><b>Fecha Solicitada:</b></div>
                                            <div class="col-sm-6" v-text="oneSolicitud.fecha_hora"></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4"><b>Agencia:</b></div>
                                            <div class="col-sm-6" v-text="oneSolicitud.agencia_nombre">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4"><b>Departamento:</b></div>
                                            <div class="col-sm-6" v-text="oneSolicitud.departamento_nombre">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-4"><b>Estado de la Solicitud:</b></div>
                                            <div class="col-sm-6">
                                                <template v-if="oneSolicitud.status">
                                                    <span class="badge badge-warning">Pendiente</span>
                                                </template>
                                                <template v-else>
                                                    <span class="badge badge-info">Realizado</span>
                                                </template>
                                            </div>
                                        </div>
                                        <br>
                                        <table class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Comentario</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <template v-for="(sol,index) in detalleSolicitud">
                                                <tr >
                                                    <td v-text="index"></td>
                                                    <td v-text="sol.nombre"></td>
                                                    <td v-text="sol.cantidad"></td>
                                                    <td v-text="sol.comentario"></td>
                                                </tr>
                                            </template>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </main>
</template>


<script>
    import VueLoading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/vue-loading.min.css';
    import swalPlugin from '../../../../public/js/VueSweetalert2.js';
    Vue.use(swalPlugin);
    Vue.use(VueLoading);
    let $ = jQuery;
    export default {
        props : ['ruta'],
        data(){
            return{
                solicitud : [],
                modal : 0,
                detalleSolicitud : [],
                oneSolicitud : [],
                }

        },
        computed:{

        },
        components: {
            Loading: VueLoading
        },
        methods : {
            open() {

                let loader = this.$loading.show();
                setTimeout(() => loader.hide(),1000);
            },
            show() {
                this.visible = true
            },
            getDetalle(){
                    let me = this;
                    let url = me.ruta + '/solicitud/getAllSolicitud';
                    axios.get(url).then(function(response){
                            me.solicitud = response.data.solicitud;
                    })
                    .catch(function (error){
                            console.log(error);
                    });
                   // me.dataTable();
                                },
            verSolicitud(id){
                let me = this;
                me.modal = 1;
                let url = me.ruta + '/solicitud/getSolicitud/'+id;
                axios.get(url).then(function(response){
                        //console.log(response.data);
                        me.detalleSolicitud = response.data.detalleSolicitud;
                        me.oneSolicitud = response.data.solicitud;
                    })
                    .catch(function (error){
                        console.log(error);
                    });

            },
            cerrarModal(){
                this.modal = 0;
                this.detalleSolicitud = [];
                this.oneSolicitud = [];
            },
            realizado(id){

                let me = this;
                let url = me.ruta + '/solicitud/solicitudListo/'+id;
                axios.post(url).then(function(response){
                        //console.log(response.data);
                        me.$swal({
                            type: 'success',
                            title: 'SOLICITUD...',
                            text: 'Tu solicitud a sido despachada!'
                        })
                        me.getDetalle();
                    })
                    .catch(function (error){
                        console.log(error);
              });

            },
            descargarPdf(id){
                window.open('http://10.60.81.12:81/sisPlanilla/public/solicitud/pdf/'+id,'_blank');
            },


        },
        mounted() {
            let me = this;
           // alert("mounted");
            this.open();
            this.getDetalle();
            setTimeout(function() {
                var table = $('#example').DataTable( {
                    buttons: [ 'excel', 'pdf' ]
                } );

                table.buttons().container()
                    .appendTo( '#example_wrapper .col-md-6:eq(0)' );
            },500);
            setInterval(function() {
                me.getDetalle();
            },1000);


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
