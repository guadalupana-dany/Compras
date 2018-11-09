<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
            </ol>
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>Producto de Bodega
                    </div>
                    <div class="card-body">
                       <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Op</th>
                                    <th>Nombre</th>
                                    <th>Proveedor</th>
                                    <th>C-M-AN</th>
                                    <th>P-M-AN</th>
                                    <th>T-M-AN</th>
                                    <th>C-M-AC</th>
                                    <th>P-M-AC</th>
                                    <th>T-M-AC</th>
                                    <th>Saldo</th>
                                    <th>Stock</th>
                                    <th>Precio Unitario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(prodB,index) in productoBodega">
                                <tr >
                                    <td v-text="index"></td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="tooltip" title="Editar este producto" @click="modalUpdate(prodB)" >
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </td>
                                    <td v-text="prodB.nombre"></td>
                                    <td v-text="prodB.proveedor"></td>
                                    <td v-text="prodB.can_mes_anterior"></td>
                                    <td v-text="prodB.pre_u_mes_anterior"></td>
                                    <td v-text="prodB.tot_mes_anterior"></td>
                                    <td v-text="prodB.can_mes_actual"></td>
                                    <td v-text="prodB.pre_u_mes_actual"></td>
                                    <td v-text="prodB.tot_mes_actual"></td>
                                    <td v-text="prodB.total_saldo"></td>
                                    <td v-text="prodB.total_stock"></td>
                                    <td v-text="prodB.total_unitario"></td>
                                </tr>
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
                            <h4 class="modal-title">Detalle del Producto</h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="card">
                                    <div class="card-header">
                                        <i class="fa fa-align-justify"></i>Detalle del Producto
                                    </div>
                                    <div v-show="errorDetalle" class="form-group">
                                            <div class="text-center text-error">
                                                <div v-for="error in arrayError" :key="error" v-text="error">

                                                </div>
                                            </div>
                                    </div>
                                    <div class="card-body">
                                        <template v-for="(prodB) in detalleProductoBodega">
                                        <div class=row>
                                            <div class="col-sm-3">Nombre:</div>
                                            <div class="col-md-4" v-text="prodB.nombre"></div>
                                        </div>
                                        <br>
                                        <div class=row>
                                            <div class="col-sm-3">Precio Unitario:</div>
                                            <div class="col-md-4" v-text="prodB.total_unitario"></div>
                                        </div>
                                        <br>
                                        <div class=row>
                                            <div class="col-sm-3">Cantidad:</div>
                                            <div class="col-md-4" v-text="prodB.total_stock"></div>
                                        </div>
                                        <br>
                                        <div class=row>
                                            <div class="col-sm-3">Total Pago:</div>
                                            <div class="col-md-4" ><input type="text" class="form-control" v-model="total_pago"></div>
                                        </div>
                                        <br>
                                        <div class=row>
                                            <div class="col-sm-3">Cantidad Ingresar:</div>
                                            <div class="col-md-4"><input type="number" class="form-control" v-model="cantidadUpdate"></div>
                                        </div>
                                        <br>
                                        <div class=row>
                                            <div class="col-sm-3">Proveedor:</div>
                                            <div class="col-md-4"><input type="text" class="form-control" v-model="prodB.proveedor"></div>
                                        </div>
                                        <br>
                                        <div class=row>
                                            <div class="col-sm-3">Precio Unitario:</div>
                                            <div class="col-md-4" >{{ precioUnitario = calcularTotal }}</div>
                                        </div>
                                        </template>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" @click="updateStock()">Actualizar Stock</button>
                            <button type="button" class="btn btn-primary" @click="cerrarModal()">Cerrar</button>
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
                productoBodega : [],
                modal : 0,
                detalleProductoBodega : [],

                cantidadUpdate : 0,
                total_pago : 0,
                precioUnitario : 0,
                nombreProveedor : '',

                idProducto : 0,

                detalleSolicitud : [],
                oneSolicitud : [],

                errorDetalle : 0,
                arrayError : []
                }

        },
        computed:{
                calcularTotal: function(){
                     var resultado = this.total_pago / this.cantidadUpdate;
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
            //metodo que nos devuelve todos los productos que hay en bodega
            getProdcutoBodega(){
                    let me = this;
                    let url = me.ruta + '/solicitud/productoBodega';

                    axios.get(url).then(function(response){
                        me.productoBodega = response.data.productoB;
                    })
                    .catch(function (error){
                            console.log(error);
                    });
                   // me.dataTable();

            },
            //metodo que actualiza el stock en bodega osea cuando se compra producto
            modalUpdate(data=[]){
                let me = this;
                me.modal = 1;
                me.detalleProductoBodega.push({
                    nombre : data['nombre'],
                    idControl : data['idControl'],
                    total_stock : data['total_stock'],
                    total_unitario : data['total_unitario'],
                    total_saldo : data['total_saldo'],
                    proveedor : data['proveedor']
                });
            },
            //metodo que verifica el envio de la actualizacion a bodega
            validarEnvio(){
                if(!this.cantidadUpdate) this.arrayError.push("Ingrese Cantidad");
                if(!this.total_pago) this.arrayError.push("Ingrese total de Pago");
                if(this.cantidadUpdate < 1) this.arrayError.push("Cantidad Mayor a 1");
                if(this.cantidadUpdate < 1) this.arrayError.push("Precio Mayor a 1");
                if(this.arrayError.length) this.errorDetalle = 1;
                return this.errorDetalle;
            },
            cerrarModal(){
                this.modal = 0;
                this.detalleSolicitud = [];
                this.oneSolicitud = [];
                this.detalleProductoBodega = [];
                this.cantidadUpdate = 0;
                this.idProducto = 0;
                this.errorDetalle = 0;
                this.arrayError = [];
                this.cantidadUpdate = 0;
                this.total_pago = 0;
                this.precioUnitario = 0;
            },
            //metodo que actualiza el stock
            updateStock(){

                this.errorDetalle = 0;
                this.arrayError = [];
                if(this.validarEnvio()){
                    return;
                }
                let me = this;
                me.$swal({
                            title: 'Actualizar Stock?',
                            text: 'Estas segura de actualizar el Stock',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Si Actualizar!',
                            cancelButtonText: 'No, Cancelar!',
                            showCloseButton: true,
                            showLoaderOnConfirm: true
                            }).then((result) => {
                            if(result.value) {
                                    let url = me.ruta + '/solicitud/updateProductoBodega';
                                    axios.post(url,{
                                      'cantidadUpdate' : me.cantidadUpdate,
                                      'total_pago'  : me.total_pago,
                                      'precioUnitario' : me.precioUnitario,
                                      'nombreProveedor' : me.nombreProveedor,
                                      'data' : me.detalleProductoBodega
                                        }).then(function(response){
                                            me.getProdcutoBodega();
                                            me.$swal('Actualizado', 'As Actualizado el stock', 'success')
                                            me.cerrarModal();
                                        })
                                        .catch(function (error){
                                                console.log(error);
                                    });

                            } else {
                                this.$swal('Cancelado', 'Cancelaste la Actualizacion', 'info')
                            }
                    })


            }
        },
        mounted() {
            let me = this;
           // alert("mounted");
            this.open();
            this.getProdcutoBodega();
            setTimeout(function() {
                var table = $('#example').DataTable( {
                    buttons: [ 'excel', 'pdf' ]
                } );

                table.buttons().container()
                    .appendTo( '#example_wrapper .col-md-6:eq(0)' );
            },800);

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
