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
                        <i class="fa fa-align-justify"></i> Usuarios
                        <button type="button" @click="abrirModal('user','registrar')" class="btn btn-secondary" >
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="name" >Nombre</option>
                                      <option value="email">Email</option>
                                    </select>
                                    <input type="text" v-model="buscar" class="form-control" @keyup.enter="listarUsuario(1,buscar,criterio)" placeholder="Texto a buscar">
                                    <button type="submit" class="btn btn-primary" @click="listarUsuario(1,buscar,criterio)"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Opciones</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Roles</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="user in arrayUsuario" :key="user.id">
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" @click="abrirModal('user','actualizar',user)" >
                                          <i class="icon-pencil"></i>
                                        </button> &nbsp;
                                        <template v-if="user.estado">
                                            <button type="button" class="btn btn-danger btn-sm" @click="eliminarUsuario(user.id)">
                                            <i class="icon-trash"></i>
                                            </button>
                                        </template>
                                        <template v-else>
                                            <button type="button" class="btn btn-info btn-sm"  @click="activarUsuario(user.id)">
                                            <i class="icon-check"></i>
                                            </button>
                                        </template>
                                        <button type="button" class="btn btn-success btn-sm"  @click="abrirModal2(user.id)">
                                            <i class="icon-key"></i>
                                        </button>
                                    </td>
                                    <td v-text="user.name"></td>
                                    <td v-text="user.email"></td>
                                    <td>
                                        <div v-for="rol in user.roles" :key="rol.id" >
                                           - {{ rol.nombre }}
                                        </div>
                                    </td>
                                    <td>
                                        <template v-if="user.estado">
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
                                        <input type="text"  class="form-control" v-model="name" placeholder="Nombre de usuario" >
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" v-model="email" placeholder="Enter Email" >
                                    </div>
                                </div>
                                <div class="form-group row" v-if="tipoAccion == 1 ">
                                    <label class="col-md-3 form-control-label" for="email-input">Password</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" v-model="password" placeholder="Enter Password" >
                                    </div>
                                </div>
                         <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="roles-input">Roles</label>
                            <div class="col-md-9">

                                <div class="form-check" v-for="rol in arrayRoles" :key="rol.id" id="divRol">

                                    <div v-if="compararRoles(rol.id) == 1">
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="roles[]" :value='rol.id' checked>
                                            {{ rol.nombre }}
                                        </label>
                                    </div>
                                    <div v-else>
                                        <label class="form-check-label">
                                            <input type="checkbox" class="form-check-input" name="roles[]" :value='rol.id'  >
                                            {{ rol.nombre }}
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                                <div v-show="errorUsuario" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMsjUsuario" :key="error" v-text="error"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarUsuario()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarUsuario()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>

            <div class="modal fade"  tabindex="-1" :class="{'mostrar' : modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Actualizando Contraseña</h4>
                            <button type="button" class="close" @click="cerrarModal2()" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Password</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" v-model="password" placeholder="Enter Password" >
                                    </div>
                                </div>
                                <div v-show="errorUsuario" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMsjUsuario" :key="error" v-text="error"></div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal2()">Cerrar</button>
                            <button type="button" class="btn btn-primary" @click="updatePassword()">Actualizar</button>
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
 //estas dos importaciones sirve para quitar el diley
 //la libreria a instalar fue npm install vue-loading-overlay --savtis
 import VueLoading from 'vue-loading-overlay';
 import 'vue-loading-overlay/dist/vue-loading.min.css';
 Vue.use(VueLoading);

 Vue.use(swalPlugin);

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

                //variables para los modal
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                modal2 : 0,
                //variables que se utilizaran en los usuarios
                idusuario : '',
                name : '',
                email : '',
                password : '',
                getRolUser : [],//los roles que tiene asignado el usuario

                //variables para los errores en el formulario ingreso usuario
                errorUsuario : 0,
                errorMsjUsuario : [],

                //variable de roles
                arrayRoles : [],
                checkRoles : [],
                arrayRolesUsuario : [],

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
                },

        },
    components: {
        Loading: VueLoading
    },
        methods : {
            open() {

                    let loader = this.$loading.show();
                    setTimeout(() => loader.hide(),1000)
            },
            show() {
                    this.visible = true
            },
            //lista todos los usuario del sistema
            listarUsuario(page,buscar,criterio){
                let me = this;
                var url = this.ruta+'/user?page=' + page + "&buscar=" + buscar + "&criterio=" + criterio;
                axios.get(url).then(function(response){
                    var respuesta = response.data;
                    me.arrayUsuario = respuesta.user.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error){
                });
            },
            cambiarPaginacion(page,buscar,criterio){
                    //metodo que cambia de pagina
                let me = this;
                me.pagination.current_page = page;
                me.listarUsuario(page,buscar,criterio);
            },
            //metodo que registra a un usuario con sus roles
            registrarUsuario(){
                var me = this;
                //obtenemos los roles chequeados y los ponemos en un array para enviar ese arreglo al controller
                let chekR =  document.getElementsByName('roles[]');
                for(var i = 0;i<chekR.length;i++){
                    if(chekR[i].checked){
                       me.checkRoles.push(chekR[i].value);
                    }
                }
                if(me.validarUsuario()){
                    return;
                }
                var url = this.ruta+'/user/store';
                axios.post(url,{
                    'name': this.name,
                    'email': this.email,
                    'password': this.password,
                    'data' : this.checkRoles
                }).then(function (response) {
                   me.$swal(
                    'Usuario Insertado',
                    'Exitosamente!',
                    'success'
                   );
                   me.cerrarModal();
                   me.listarUsuario(1,'','name');

                }).catch(function (error){
                    console.log(error);
                });
            },
            //metodo que list todos lo roles de ls BD y los coloca en un array
            listadoRoles: function(){
               // var f = new Date();

                var me = this;
                this.isLoading = true;
                axios.get(this.ruta+'/rol/roles').then(function(response){
                    var respuesta = response.data;
                    me.arrayRoles = respuesta.roles;
                    setTimeout(function(){

                    },10000);
                    me.isLoading = false
                })
                .catch(function (error){
                    console.log(error);
                });
            },
            //metodo que compara los roles del usuario con los que hay para chequearlos
            compararRoles: function(idRol){
                let retorno = 0;
                    this.getRolUser.forEach((rst) =>{
                       if(idRol == rst.id){
                            retorno = 1;
                        }
                    });
                    return retorno;
            },
            //metodo que nos trae los roles del usuario a editar y los colacamos en un arreglo
            rolesUsuario(id){
                let me =this;
                var url = this.ruta+'/user/roles?id='+id;
                axios.get(url).then((res) =>{
                   // console.log(res.data.user);
                    var respuesta = res.data.rolesUser;
                    this.getRolUser = respuesta;
                    //this.pruebaRoles();
                })
                .catch(function (error){
                    console.log(error);
                });

            },
            //metodo que actualiza los roles o datos del usuario
            actualizarUsuario(){
                var me = this;
               let chekR =  document.getElementsByName('roles[]');
                for(var i = 0;i<chekR.length;i++){
                    if(chekR[i].checked){
                       me.checkRoles.push(chekR[i].value);
                    }
                }

               if(me.validarUsuario()){
                    return;
                }
                var url = this.ruta+'/user/actualizar';

                axios.post(url,{
                    'id': this.idusuario,
                    'name': this.name,
                    'email': this.email,
                    'password': this.password,
                    'data' : this.checkRoles
                }).then(function (response) {
                   me.$swal(
                    'Usuario Actualizado',
                    'Exitosamente!',
                    'success'
                   );
                   me.cerrarModal();
                   me.listarUsuario(1,'','name');

                }).catch(function (error){
                    console.log(error);
                });
            },
            //metodo que pone desactivo al usuario
            eliminarUsuario(id){
               let me = this;

                    me.$swal({
                    title: 'Desea Eliminar Este Usuario?',
                    text: "Desactivaras el Usuario!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Eliminar!'
                    }).then((result) => {
                    if (result.value) {
                        axios.post(this.ruta+'/user/desactivar',{
                            'id': id
                        }).then(function (response) {
                             me.$swal(
                            'Eliminada!',
                            'Tu Usuario fue eliminado.',
                            'success'
                            );
                            me.listarUsuario(1,'','nombre');
                        }).catch(function (error) {
                            console.log(error);
                        });

                    }
                    })
            },
            //metodo que pone Activa al usuario
            activarUsuario(id){
               let me = this;

                    me.$swal({
                    title: 'Desea Activar El Usuario?',
                    text: "Activaras el Usuario!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Agregar!'
                    }).then((result) => {
                    if (result.value) {
                        axios.post(this.ruta+'/user/activar',{
                            'id':id
                        }).then(function (response) {
                             me.$swal(
                            'Activado!',
                            'Tu Usuario fue activado.',
                            'success'
                            );
                            me.listarUsuario(1,'','nombre');
                        }).catch(function (error) {
                            console.log(error);
                        });

                    }
                    })
            },
            //metodo que valida el formulario
            validarUsuario(){
                this.errorUsuario = 0;
                this.errorMsjUsuario = [];

                if(!this.name) this.errorMsjUsuario.push('ingrese el nombre');
                if(!this.email) this.errorMsjUsuario.push('ingrese el Email');
                if(!this.password) this.errorMsjUsuario.push('ingrese el Password');
                if(!this.checkRoles.length) this.errorMsjUsuario.push('marke al menos un rol');

                if(this.errorMsjUsuario.length) this.errorUsuario = 1;
                return this.errorUsuario;
            },
            //valida el password no este vacio
            validarPassword(){
                this.errorUsuario = 0;
                this.errorMsjUsuario = [];
                if(!this.password) this.errorMsjUsuario.push('ingrese el Password');
                if(this.errorMsjUsuario.length) this.errorUsuario = 1;
                return this.errorUsuario;
            },
            //metod que actualiza el password
            updatePassword(){

                if(this.validarPassword()){
                    return;
                }
               var url = this.ruta+'/user/updatePassword';
               let me = this;
                axios.post(url,{
                    'id': this.idusuario,
                    'password': this.password,
                }).then(function (response) {

                   me.$swal(
                    'La contraseña fue actualizada',
                    'Exitosamente!',
                    'success'
                   );
                   me.cerrarModal2();
                   me.listarUsuario(1,'','name');

                }).catch(function (error){
                    console.log(error);
                });
            },
            //metodo que habre el modal
            abrirModal(modelo, accion, data = []){
                  this.open();
                  switch(modelo){

                        case "user":
                        {
                            switch(accion){
                                    case "registrar":
                                    {
                                        let me = this;
                                        this.listadoRoles();
                                        setTimeout(function(){
                                            me.modal = 1;
                                            me.tituloModal = "Create Usuario";
                                            me.tipoAccion = 1;
                                            me.name = '';
                                            me.email = '';
                                            me.password = '';
                                            me.idusuario = '';
                                            me.checkRoles = [];
                                            me.getRolUser =[];
                                        }, 300);

                                        break;
                                    }
                                    case "actualizar":
                                    {
                                        let me = this;
                                        me.listadoRoles();
                                        me.rolesUsuario(data['id'])
                                        setTimeout(function(){
                                        me.modal = 1;
                                        me.tituloModal = "Update Usuario";
                                        me.tipoAccion = 2;
                                        me.name = data['name'];
                                        me.email = data['email'];
                                        me.idusuario = data['id'];
                                        me.password = data['password'];
                                         }, 300);
                                        break;
                                    }
                            }

                        }

                  }
            },
            abrirModal2(id){
                this.modal2 = 1;
                this.idusuario = id;
            },
            cerrarModal2(){
                this.modal2 = 0;
                this.password = '';
                this.idusuario = '';
                this.errorMsjUsuario = [];
                this.errorUsuario = 0;
            },
            //metodo que cierra el modal
            cerrarModal(){
                this.tipoAccion = 1;
                this.tituloModal = '';
                this.modal = 0;
                this.idusuario ='';
                this.name = '';
                this.email = '';
                this.password = '';
                this.errorMsjUsuario = [];
                this.errorUsuario = 0;
                this.checkRoles =[];
                this.arrayRoles = [];

              /*   var checkeados = document.getElementsByName("roles[]");
                checkeados.removeAttr('cheched');
               for(var e = 0;e < checkeados.length;e++){
                    checkeados[e].checked = false;
                }*/


            },
        },
        mounted() {
           this.listarUsuario(1,this.buscar,this.criterio);
           this.listadoRoles();
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
