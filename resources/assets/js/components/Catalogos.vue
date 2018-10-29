<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
            </ol>
            <div class="container-fluid">
                <div class="card">

                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> CATALOSGOS
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link" href="#" @click="cargaDatos(0)">CATEGORIA</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" @click="cargaDatos(1)">/  PRODUCTO</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" @click="cargaDatos(2)">/  AGENCIAS</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" @click="cargaDatos(3)">/  DEPARTAMENTOS</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">

                        <template v-if="cataSeleccionado==0">
                            <table class="table table-bordered table-striped table-sm" id="table_id">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Nombre</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="categoria in arrayCategoria" :key="categoria.id">
                                    <td v-text="categoria.id"></td>
                                    <td v-text="categoria.nombre"></td>
                                </tr>
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
                        </template>
                        <template v-if="cataSeleccionado==1">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Categoria</th>
                                    <th>Nombre Producto</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(producto,index) in arrayProducto" :key="producto.id">
                                    <td v-text="index"></td>
                                    <td v-text="producto.nombre_cat"></td>
                                    <td v-text="producto.nombre"></td>
                                </tr>
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
                        </template>
                        <template v-if="cataSeleccionado==2">
                            <table class="table table-bordered table-striped table-sm" id="table_id">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>id</th>
                                    <th>Nombre</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(agencia,index) in arrayAgencia" :key="agencia.id">
                                    <td v-text="index+1"></td>
                                    <td v-text="agencia.id"></td>
                                    <td v-text="agencia.nombre"></td>
                                </tr>
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
                        </template>
                        <template v-if="cataSeleccionado==3">
                            <table class="table table-bordered table-striped table-sm" id="table_id">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>id</th>
                                    <th>Nombre</th>
                                    <th>Agencias</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(departamento,index) in arrayDepartamento" :key="departamento.id">
                                    <td v-text="index+1"></td>
                                    <td v-text="departamento.id"></td>
                                    <td v-text="departamento.nombre"></td>
                                    <td >
                                        <div v-for="departamento in departamento.agencia" :key="departamento.id" >
                                            - {{ departamento.nombre }}
                                        </div>
                                    </td>
                                </tr>
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
                        </template>
                    </div>
                </div>
            </div>
        </main>
</template>


<script>

    export default {
        props : ['ruta'],
        data(){
            return{
                //var para la paginacion
                offset : 3,
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },


                arrayCategoria : [],
                arrayProducto : [],
                arrayAgencia : [],
                arrayDepartamento : [],
                cataSeleccionado : 0,
                }

        },
        components: {
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
            },

        },
        methods:{

            cambiarPaginacion(page){
                //metodo que cambia de pagina
                let me = this;
                me.pagination.current_page = page;
                me.cargaDatos(this.cataSeleccionado,page);
            },

            cargaDatos(op,page){
                let me = this;
                me.cataSeleccionado = op;

                var url = me.ruta+'/upLoad/MostrarData?page='+page;
                axios.post(url,{'cataSelec' : me.cataSeleccionado})
                .then(function(response){
                    if(response.data.categoria){
                        me.arrayCategoria = response.data.categoria.data;
                        me.pagination = response.data.pagination;
                        me.arrayProducto =[];
                        me.arrayAgencia =[];
                        me.arrayDepartamento =[];
                    }
                    if(response.data.producto){
                        me.arrayProducto = response.data.producto.data;
                        me.pagination = response.data.pagination;
                        me.arrayCategoria =[];
                        me.arrayAgencia =[];
                        me.arrayDepartamento =[];
                    }
                    if(response.data.agencia){
                        me.arrayAgencia = response.data.agencia.data;
                        me.pagination = response.data.pagination;
                        me.arrayCategoria =[];
                        me.arrayProducto =[];
                        me.arrayDepartamento =[];
                    }
                    if(response.data.departamento){
                        me.arrayDepartamento = response.data.departamento.data;
                        me.pagination = response.data.pagination;
                        me.arrayCategoria =[];
                        me.arrayProducto =[];
                        me.arrayAgencia =[];
                    }
                })
                .catch(function (error){
                    console.log(error);
                });
            }
        },
        mounted() {
            this.cargaDatos(this.cataSeleccionado,1);
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
