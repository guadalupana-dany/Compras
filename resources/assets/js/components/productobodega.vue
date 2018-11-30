<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">BODEGA</li>
            </ol>
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>Producto de Bodega
                        <button type="button" @click="abrirModalCategoria()" class="btn btn-primary" >
                            <i class="icon-plus"></i>&nbsp;Nueva Categoria
                        </button>
                        <button type="button" @click="abrirModalProducto()" class="btn btn-primary" >
                                    <i class="icon-plus"></i>&nbsp;Nuevo Producto
                        </button>
                    </div>
                    <div class="card-body">
                        <div style="color:red">
                            <h3 >Total Saldo Global : <strong>{{ calcularTotalGlobal }}</strong></h3>
                        </div>
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
            <!--MODAL PARA LA INSERCION DE PRODUCTOS DE BODEGA-->
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

            <!-- MODAL PARA LA INSERCION DE CATEGORIA-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalCategoria}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Ingrese Categoria</h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="card">
                                    <div class="card-header">
                                        <i class="fa fa-align-justify"></i>CATEGORIA
                                    </div>
                                    <div v-show="errorCate" class="form-group">
                                            <div class="text-center text-error">
                                                <div v-for="error in errorCate" :key="error" v-text="error">

                                                </div>
                                            </div>
                                    </div>
                                    <div class="card-body">
                                        <div class=row>
                                            <div class="col-sm-2">Nombre:</div>
                                            <div class="col-md-4"><input type="text" class="form-control"  v-model="descCategoria"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" @click="guardarCategoria()">Guardar</button>
                            <button type="button" class="btn btn-primary" @click="cerrarModal()">Cerrar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <!-- MODAL PARA LA INSERCION DE PRODUCTO CUALQUIERA-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalProducto}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">PRODUCTO</h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="card">
                                    <div class="card-header">
                                        <i class="fa fa-align-justify"></i>Producto
                                    </div>
                                    <div v-show="errorProd" class="form-group">
                                            <div class="text-center text-error">
                                                <div v-for="error in errorPro" :key="error" v-text="error">

                                                </div>
                                            </div>
                                    </div>
                                    <div class="card-body">
                                        <div class=row>

                                                <div class="col-sm-2"> <label for="inputEmail3" class="control-label">Categoria:</label></div>
                                                 <div class="col-md-4">
                                                        <vSelect
                                                            :on-search="selectCategoria"
                                                            label="nombre"
                                                            :options="ArrayCategoria"
                                                            placeholder ="Buscar categoria ..."
                                                            :onChange="getDatosCategoria">
                                                        </vSelect>
                                                </div>
                                        </div>
                                        <br>
                                        <div class=row>
                                            <div class="col-sm-2">Nombre:</div>
                                            <div class="col-md-4"> <input type="text" class="form-control" v-model="descProducto"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" @click="guardarProducto()">GUARDAR</button>
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
    import vSelect from 'vue-select'
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
                arrayError : [],
                //variable para la descripcion del producto
                descProducto : '',
                modalProducto : 0,
                errorPro : [],
                errorProd : 0,
                //variable para descripcion de categoria
                descCategoria : '',
                modalCategoria : 0,
                errorCate : [],
                errorCateg : 0,

                //
                ArrayCategoria : [],
                IdCategoria : 0,
                }

        },
        computed:{
                calcularTotal: function(){
                     var resultado = this.total_pago / this.cantidadUpdate;
                    return resultado;
                },
                calcularTotalGlobal: function(){
                     var resultado = 0;
                    for(var i=0;i<this.productoBodega.length;i++){

                        resultado = (resultado) + parseFloat(this.productoBodega[i].total_saldo);
                    }

                    return resultado.toFixed(2);
                },
        },
        components: {
            Loading: VueLoading,
            vSelect
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
                this.modalCategoria = 0;
                this.modalProducto = 0;
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
                this.descProducto = '';
                this.descCategoria = '';
                this.errorPro = [];
                this.errorCate = [];
                this.errorProd = 0;
                this.errorCateg = 0;
                this.IdCategoria = 0;
                this.ArrayCategoria = [];
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


            },
            abrirModalCategoria(){
                this.modalCategoria = 1;
            },
            guardarCategoria(){
                let me = this;

                   this.errorCate = [];
                   this.errorCateg = 0;
                   if(this.validarCategoria()){
                        return;
                   }else{
                       ///upLoad/categoria
                       var url = me.ruta + '/upLoad/categoria';
                        axios.post(url,{
                                'descCategoria': me.descCategoria
                            }).then(function (response) {
                            me.$swal(
                                'Categoria Insertada',
                                'Exitosamente!',
                                'success'
                            );
                            me.cerrarModal();

                            }).catch(function (error){
                                console.log(error);
                        });
                   }
            },
            validarCategoria(){
                 if(!this.descCategoria) this.errorCate.push("Ingrese Descripcion de la categoria");
                 if(this.errorCate.length) this.errorCateg = 1;
                 return this.errorCateg;
            },
            abrirModalProducto(){
                this.modalProducto = 1;
            },
            guardarProducto(){
                let me = this;


                   this.errorPro = [];
                   this.errorProd = 0;
                   if(this.validarProducto()){
                        return;
                   }else{
                      var url = me.ruta + '/upLoad/producto';
                        axios.post(url,{
                                'descProducto': me.descProducto,
                                'IdCategoria' : me.IdCategoria
                            }).then(function (response) {
                            me.$swal(
                                'Producto Insertado',
                                'Exitosamente!',
                                'success'
                            );
                            me.cerrarModal();

                            }).catch(function (error){
                                console.log(error);
                        });
                   }
            },
            validarProducto(){
                 if(!this.descProducto) this.errorPro.push("Ingrese Descripcion del Producto");
                 if(this.errorPro.length) this.errorProd = 1;
                 return this.errorProd;
            },
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
            getDatosCategoria(val1){
                let me = this;
                me.loading = true;
                if(val1 !== null){
                     me.IdCategoria = val1.id;//almacenamos el id
                     console.log("categoria seleccionada = "+me.IdCategoria);
                  /*  var url = this.ruta + '/solicitud/selectProducto?id='+me.IdCategoria;
                    axios.get(url).then(function(response){
                           me.ArrayProducto = response.data.producto;
                    }).catch(function(error){
                        console.log("Error en listAgencia");
                    });*/
                }else{
                    me.ArrayCategoria = [];
                }
            },
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
