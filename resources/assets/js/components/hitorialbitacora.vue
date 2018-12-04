<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Mi Coope Guadalupana</li>
            </ol>
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i>Solicitudes Rechazadas
                    </div>
                    <div class="card-body">
                       <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Proveedor</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Total_Pagado</th>
                                    <th>P/u</th>
                                    <th>Fecha Compra</th>
                                    <th>Comprado por</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(sol,index) in compras">

                                            <tr >
                                                <td v-text="sol.proveedor"></td>
                                                <td v-text="sol.producto"></td>
                                                <td v-text="sol.cantidad"></td>
                                                <td v-text="sol.total_pagar"></td>
                                                <td v-text="sol.precio_unitario"></td>
                                                <td v-text="sol.fecha_compra"></td>
                                                <td v-text="sol.name"></td>
                                            </tr>

                                </template>
                            </tbody>
                         </table>

                    </div>
                </div>

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
                compras : [],
                }

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
            getCompra(){
                    let me = this;
                    let url = me.ruta + '/solicitud/getAllCompra';
                    axios.get(url).then(function(response){
                            me.compras = response.data.compras;
                    })
                    .catch(function (error){
                            console.log(error);
                    });
                   // me.dataTable();
           },



        },
        mounted() {
            let me = this;
           // alert("mounted");
            this.open();
            this.getCompra();
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
