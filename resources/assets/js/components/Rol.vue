<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item"><a href="#">Admin</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Roles
                        <button type="button" @click="abrirModal('rol','registrar')" class="btn btn-secondary" >
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre" >Nombre</option>
                                      <option value="descripcion">Descripcion</option>
                                    </select>
                                    <input type="text" v-model="buscar" class="form-control" @keyup.enter="listarRol(1,buscar,criterio)" placeholder="Texto a buscar">
                                    <button type="submit" class="btn btn-primary" @click="listarRol(1,buscar,criterio)"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Nombre</th>
                                    <th>Descripcion</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="rol in arrayRol" :key="rol.id">
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('rol','actualizar',rol)" >
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="rol.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminarRol(rol.id)">
                                            <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm"  @click="activarRol(rol.id)">
                                            <i class="icon-check"></i>
                                            </button>
                                        </template>
                                    </td>
                                    <td v-text="rol.nombre"></td>
                                    <td v-text="rol.descripcion"></td>
                                    <td>
                                        <template v-if="rol.estado">
                                            <span class="badge badge-success">Activo</span>
                                        </template>
                                        <template v-else>
                                            <span class="badge badge-danger">Desactivado</span>
                                        </template>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginacion(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active': '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginacion(page,buscar,criterio)" v-text="page"></a>
                                </li>
                                <li class="page-item"  v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPaginacion(pagination.current_page + 1,buscar,criterio)" >Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade"  tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                    <div class="col-md-9">
                                        <input type="text"  class="form-control" v-model="nombre" placeholder="Nombre de usuario" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Descripcion</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" v-model="descripcion" placeholder="Enter Descripcion" >
                                    </div>
                                </div>

                                <div v-show="errorRol" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMsjRol" :key="error" v-text="error"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarRol()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarRol()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

        </main>
</template>


<script>
 import swalPlugin from '../../../../public/js/VueSweetalert2.js';
 Vue.use(swalPlugin);

    export default {
        props : ['ruta'],
        data(){
            return{
                arrayRol : [],

               //variables para la paginacion y la busqueda
                buscar : '',
                criterio : 'nombre',
                offset : 3,
                pagination : {
                        'total' : 0,
                        'current_page' : 0,
                        'per_page' : 0,
                        'last_page' : 0,
                        'from' : 0,
                        'to' : 0,
                         },

                //variables para los modal
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,

                //variables que se utilizaran en los usuarios
                idrol : '',
                nombre : '',
                descripcion : '',

                //variables para los errores en el formulario ingreso usuario
                errorRol : 0,
                errorMsjRol : [],


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
        methods : {
            listarRol(page,buscar,criterio){
                let me = this;

                var url = this.ruta + '/rol?page=' + page + "&buscar=" + buscar + "&criterio=" + criterio;
                axios.get(url).then(function(response){
                    var respuesta = response.data;
                    me.arrayRol = respuesta.roles.data;


                    me.pagination= respuesta.pagination;
                })
                .catch(function (error){
                    console.log(error);
                });
            },
            cambiarPaginacion(page,buscar,criterio){
                    //metodo que cambia de pagina
                let me = this;
                me.pagination.current_page = page;
                me.listarRol(page,buscar,criterio);
            },
            registrarRol(){
                var me = this;
                if(me.validarRol()){
                    return;
                }
                var url = this.ruta+'/rol/store';

                axios.post(url,{
                    'nombre': this.nombre,
                    'descripcion': this.descripcion
                }).then(function (response) {
                   me.$swal(
                    'Rol Insertado',
                    'Exitosamente!',
                    'success'
                   );
                   me.cerrarModal();
                   me.listarRol(1,'','nombre');

                }).catch(function (error){
                    console.log(error);
                });
            },
            actualizarRol(){
                var me = this;
                if(me.validarRol()){
                    return;
                }
                var url = this.ruta+'/rol/actualizar';

                axios.post(url,{
                    'id' : this.idrol,
                    'nombre': this.nombre,
                    'descripcion': this.descripcion
                }).then(function (response) {

                   me.$swal(
                    'Rol Actualizado',
                    'Exitosamente!',
                    'success'
                   );
                   me.cerrarModal();
                   me.listarRol(1,'','nombre');

                }).catch(function (error){
                    console.log(error);
                });
            },
            eliminarRol(id){
               let me = this;

                    me.$swal({
                    title: 'Desea Eliminar Este Rol?',
                    text: "Desactivaras el Rol!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Eliminar!'
                    }).then((result) => {
                    if (result.value) {
                        axios.post(this.ruta+'/rol/desactivar',{
                            'id': id
                        }).then(function (response) {
                             me.$swal(
                            'Eliminada!',
                            'Tu Rol fue eliminado.',
                            'success'
                            );
                            me.listarRol(1,'','nombre');
                        }).catch(function (error) {
                            console.log(error);
                        });

                    }
                    })
            },
            activarRol(id){
               let me = this;

                    me.$swal({
                    title: 'Desea Activar El Rol?',
                    text: "Activaras el Rol!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Agregar!'
                    }).then((result) => {
                    if (result.value) {
                        axios.post(this.ruta+'/rol/activar',{
                            'id':id
                        }).then(function (response) {
                             me.$swal(
                            'Activado!',
                            'Tu Rol fue activado.',
                            'success'
                            );
                            me.listarRol(1,'','nombre');
                        }).catch(function (error) {
                            console.log(error);
                        });

                    }
                    })
            },
            validarRol(){
                this.errorRol = 0;
                this.errorMsjRol = [];

                if(!this.nombre) this.errorMsjRol.push('ingrese el nombre');
                if(!this.descripcion) this.errorMsjRol.push('ingrese el descripcion');

                if(this.errorMsjRol.length) this.errorRol = 1;
                return this.errorRol;
            },
            abrirModal(modelo, accion, data = []){
                this.buscar = '';
                  switch(modelo){
                        case "rol":
                        {
                            switch(accion){
                                    case "registrar":
                                    {
                                        this.modal = 1;
                                        this.tituloModal = "Registrar Rol";
                                        this.tipoAccion = 1;
                                        this.nombre = '';
                                        this.descripcion = '';
                                        this.idrol = '';
                                        break;
                                    }
                                    case "actualizar":
                                    {
                                        this.modal = 1;
                                        this.tituloModal = "Actualizar Usuario";
                                        this.tipoAccion = 2;
                                        this.nombre = data['nombre'];
                                        this.descripcion = data['descripcion'];
                                        this.idrol = data['id'];
                                        break;

                                    }
                            }

                        }
                  }
            },
            cerrarModal(){
                this.tipoAccion = 1;
                this.tituloModal = '';
                this.modal = 0;
                this.idrol ='';
                this.nombre = '';
                this.descripcion = '';
                this.errorMsjRol = [];
                this.errorRol = 0;
            },
        },
        mounted() {
           this.listarRol(1,this.buscar,this.criterio);
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
