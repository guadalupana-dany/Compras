<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Mi Coope Guadalupana</li>
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
                                        <div v-show="errorStock" class="form-group">
                                            <div class="text-center text-error">
                                                <div v-for="error in arrayErrorStock" :key="error" v-text="error">

                                                </div>
                                            </div>
                                        </div>
                                        <div v-show="errorCampos" class="form-group">
                                            <div class="text-center text-error">
                                                <div v-for="error in arrayCamposVacios" :key="error" v-text="error">

                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <table class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio U.</th>
                                                <th>Comentario</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <template v-for="(sol,index) in detalleSolicitud">
                                                <tr >
                                                    <td v-text="index"></td>
                                                    <td v-text="sol.nombre"></td>
                                                    <template v-if="oneSolicitud.status">
                                                        <td><input type="number" v-model="sol.cantidad"  @change="validaStock(sol.productoID,sol.cantidad)"></td>
                                                    </template>
                                                    <template v-else>
                                                        <td v-text="sol.cantidad" @change="validaStock(sol.productoID,sol.cantidad)"></td>
                                                    </template>
                                                       <td><input type="text" v-model="sol.precio_unitario" ></td>
                                                    <td v-text="sol.comentario"></td>
                                                </tr>
                                            </template>
                                            </tbody>
                                        </table>
                                        <br>
                                         <div class="row">
                                            <div class="col-sm-4"><b>Total:</b></div>
                                            <div class="col-sm-6"><b>Q. {{ total = calcularTotal }}</b></div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" class="btn btn-success" @click="despacharSoli()">CULMINAR SOLICITUD</button>
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
                total : 0,
                arrayErrorStock : [], //este array es el que va contener los productos que no tienen la cantidad que es en el stock
                errorStock : 0, //variable que ve si hay errores de stock

                arrayCamposVacios : [],
                errorCampos : 0,
                }

        },
        computed:{
                calcularTotal: function(){
                    var resultado = 0;
                    for(var i=0;i<this.detalleSolicitud.length;i++){
                        resultado = resultado+(this.detalleSolicitud[i].precio_unitario*this.detalleSolicitud[i].cantidad);
                    }
                    return resultado;
                },
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
            //metod que nos da la solicitud
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
            //metodo que nos muestra solo la solicitud seleccionada
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
            //metodo que despcha o finalizsa una solicitud
            despacharSoli(){
                let me = this;
                    me.$swal({
                            title:'Despachar Solicitud?',
                            text: 'Revisa la solicitud antes de ser despachada',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Si !',
                            cancelButtonText: 'No, Cancelar!',
                            showCloseButton: true,
                            showLoaderOnConfirm: true
                            }).then((result) => {
                            if(result.value) {
                                    me.errorCampos = 0;
                                    me.arrayCamposVacios = [];

                                  if(me.validarCampos()){
                                       return;
                                   }
                                   for(var i = 0; i < me.detalleSolicitud.length; i++){
                                      me.validaStockEnviar(me.detalleSolicitud[i].nombre,me.detalleSolicitud[i].cantidad,me.detalleSolicitud[i].productoID);
                                   }

                                  setTimeout(function() {

                                            if(me.errorStock){
                                                return ;
                                            }else{

                                                 let url = me.ruta + '/solicitud/solicitudListo';
                                                axios.post(url,{
                                                    'total_gasto' : me.total,
                                                    'idSolicitud' : me.oneSolicitud.id_Soli,
                                                    'detalleSoli' : me.detalleSolicitud
                                                }).then(function(response){
                                                    //console.log(response.data);
                                                    me.$swal({
                                                        type: 'success',
                                                        title: 'SOLICITUD...',
                                                        text: 'Tu solicitud a sido despachada!'
                                                    })
                                                    me.getDetalle();
                                                    me.cerrarModal();
                                                })
                                                .catch(function (error){
                                                    console.log(error);
                                                    });

                                            }

                                    },800);

                            } else {
                                this.$swal('Cancelado', 'Cancelaste el Despacho', 'info')
                            }
                    })


            },
            cerrarModal(){
                this.modal = 0;
                this.detalleSolicitud = [];
                this.oneSolicitud = [];
                this.errorStock = 0;
                this.arrayErrorStock = [];
                this.errorCampos =0;
            },
            //metodo que descarga en pdf el detalle de la solicitud
            descargarPdf(id){
                window.open('http://10.60.81.12:81/sisPlanilla/public/solicitud/pdf/'+id,'_blank');
            },
            //mtodo que valida el stock en bodega
            validaStock(idProducto,cantidad){
                let me = this;
                var url = this.ruta + '/solicitud/validarExitenciaProducto?idProducto='+idProducto;
                axios.get(url,{
                    'idProducto' : idProducto
                }).then(function(response){
                    if(response.data.result){
                        if(cantidad >= response.data.total_stock){
                            me.$swal({
                                type: 'error',
                                title: 'Error...',
                                text: 'No hay en stock la cantidad que requieres '+response.data.total_stock+' Lo que hay en Bodega'
                            })
                        }
                    }
                }).catch(function(error){
                    console.log("Error en listAgencia");
                });
            },
            //metodo que valida el stock en bodega cuando precionan el boton despachar
            validaStockEnviar(nombre,cantidad,idProducto){
                let me = this;
                me.errorCampos = 0;
                me.arrayCamposVacios = [];
                    let url = me.ruta + '/solicitud/validarExitenciaProducto?idProducto='+idProducto;

                        axios.get(url).then(response => {
                            if(response.data.result){
                              if(cantidad >= response.data.total_stock){

                                    me.arrayErrorStock.push("La cantidad del Producto " +  nombre + "No es suficiente en el stock.. En Bodega solo hay "+response.data.total_stock);
                                    me.errorStock = 1

                                }
                            }
                        });


              //  if(me.arrayCamposVacios.length) me.errorCampos = 1;
              //  return me.errorCampos;

            },
            //metodo que valida los campos
            validarCampos(){
                let me = this;
                me.errorStock = 0;
                me.arrayErrorStock = [];
                console.log("validarCampos");
                for(var i = 0; i < me.detalleSolicitud.length; i++){
                    if(!me.detalleSolicitud[i].cantidad) me.arrayCamposVacios.push("ingrese cantidad en "+me.detalleSolicitud[i].nombre);
                    if(me.detalleSolicitud[i].cantidad < 1) me.arrayCamposVacios.push("La cantidad Mayor a 1 en "+me.detalleSolicitud[i].nombre);
                    if(!me.detalleSolicitud[i].precio_unitario) me.arrayCamposVacios.push("ingrese precio en "+me.detalleSolicitud[i].nombre);
                    if(me.detalleSolicitud[i].precio_unitario < 1) me.arrayCamposVacios.push("El precio Mayor a 1 en "+me.detalleSolicitud[i].nombre);

                }
                if(me.arrayCamposVacios.length) me.errorCampos = 1;
                return me.errorCampos;
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
            },800);
          /*  setInterval(function() {
                me.getDetalle();
            },1000);*/


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
