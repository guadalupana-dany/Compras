<template>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
            </ol>
            <div class="container-fluid">
                <div class="card">
                    <template v-if="errorCargaMasiva != '' ">
                        <div class="alert alert-danger alert-dismissible">
                            <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                            <p v-text="errorCargaMasiva"></p>
                        </div>
                    </template>
                    <template v-if="exitoCargaMasiva != '' ">
                        <div class="alert alert-success alert-dismissible">
                            <h4><i class="icon fa fa-check"></i> Alert!</h4>
                            <p v-text="exitoCargaMasiva"></p>
                        </div>
                    </template>
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> Cargas Masivas (SOLO ARCHIVOS CSV)
                        <template v-if="!addDepartamento">
                            <button type="button" class="btn btn-secondary" @click="viewDepartamento()">
                            <i class="icon-plus"></i>&nbsp;Departamento
                            </button>
                        </template>
                        <template v-else>
                            <button type="button" class="btn btn-secondary" @click="viewDepartamento()">
                            <i class="icon-plus"></i>&nbsp;Subir Archivo
                            </button>
                        </template>
                    </div>
                    <div class="card-body">
                            <div class="box box-info">
                                <div class="box-body">
                                    <div class="form-group">
                                        <div v-if="!addDepartamento">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="inputEmail3" class="control-label">Tipo De carga:</label>
                                                    <select class="form-control" v-model="tipoCarga">
                                                        <option value="categoria">Categorias</option>
                                                        <option value="producto">Producto</option>
                                                        <option value="agencias">Agencias</option>
                                                        <option value="producto_Bodega">Productos Bodega</option>
                                                        <option value="stock_Bodega">Stock Bodega</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="inputEmail3">Departamento:</label>
                                                    <input type="file" class="form-control" id="file" ref="file" accept=".csv" @change="fileUpload()">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="inputEmail3" >Subir Archivo:</label>
                                                    <button class="btn btn-success form-control" @click="cargarArchivo()"><span><i class="fa fa-cloud-upload"></i></span></button>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>

                                        <div v-else>
                                                <h4>Departamentos</h4>
                                                    <div v-show="erroresAg" class="form-group">
                                                        <div class="text-center text-error">
                                                            <div v-for="error in errorArraAg" :key="error" v-text="error">
                                                                ck
                                                            </div>
                                                        </div>
                                                    </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="inputEmail3" class="control-label">Departamento:</label>
                                                        <input type="text" class="form-control" placeholder="Ingresar nombre del departamento o area" v-model="departamento">

                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:25px">
                                                    <div class="col-md-6">
                                                    <label>Seleccione las Agencias en las que pertenece:</label>
                                                        <select multiple class="form-control" v-model="arrayAgenciasSeleccionada">
                                                           <option v-for="agencia in arrayAgencias" :key="agencia.id" v-bind:value="agencia.id" v-text="agencia.nombre"></option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:25px">
                                                    <div class="col-md-3">
                                                    <button type="button" class="btn btn-block btn-primary btn-sm" @click="addepto()">Guardar</button>
                                                    </div>
                                                </div>
                                        </div>
                                        <div v-show="errores" class="form-group">
                                            <div class="text-center text-error">
                                                <div v-for="error in erroresArray" :key="error" v-text="error">

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
    import swalPlugin from '../../../../public/js/VueSweetalert2';
    Vue.use(swalPlugin);

    export default {
        props : ['ruta'],
        data(){
            return{
                addDepartamento : 0,
                tipoCarga : 'categoria', //var que ver el tipo de carga se ve a subir
                file : '',

                //var para capturar los errores
                errores : 0,
                erroresArray : [],
                errorCargaMasiva : '',
                exitoCargaMasiva : '',

                //variables para la agencia
                arrayAgencias : [],
                arrayAgenciasSeleccionada : [],
                departamento : '',
                erroresAg : 0,
                errorArraAg : []
                }

        },
        computed:{

        },
        components: {
    },
        methods:{
            //metodo que sirve para ocultar o mostrar el formulario para crear departamentos
            viewDepartamento(){
                this.addDepartamento = !this.addDepartamento;
            },
            //metodo que sube el archivo csv
            fileUpload(){
                this.file = this.$refs.file.files[0];
            },
            //metodo que se encarga de subir los archivos en carga masiva
            cargarArchivo(){
                if(this.validarCampos()){
                    return;
                }
                var url = this.ruta + '/upLoad/store';
                let me = this;
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data;charset=utf-8'
                    }
                }

                let fileAr = document.querySelector('#file').files[0];
                let formData = new FormData();
                formData.append('Accion', this.tipoCarga);
                formData.append('archivo',fileAr)
                //console.log(formData)

                axios.post(url,formData,config)
                .then(function(response){
                    if(response.data.error){
                        me.errorCargaMasiva = response.data.error;
                        setTimeout(function(){
                            me.errorCargaMasiva = '';
                            document.getElementById("file").value='';
                            me.tipoCarga = 'categoria';
                        },5000);

                    }
                    if(response.data.exito){
                        me.exitoCargaMasiva = response.data.exito;
                        setTimeout(function(){
                            me.exitoCargaMasiva = '';
                            document.getElementById("file").value='';
                            me.tipoCarga = 'categoria';
                        },5000);
                    }
                })
                .catch(function (error){
                    console.log(error);
                });
            },
            //Metodo que add los departamentos
            addepto(){
                if(this.validarDepto()){
                    return;
                }
                var url = this.ruta + '/upLoad/AddAgencias';
                let me = this;
                axios.post(url,{'departamento':me.departamento.toUpperCase(),'arrayAgenciasSeleccionada':me.arrayAgenciasSeleccionada})
                    .then(function(response){
                        if(response.data.error){
                            me.errorCargaMasiva = response.data.error;
                            setTimeout(function(){
                                me.errorCargaMasiva = '';
                                me.departamento = '';
                                me.arrayAgenciasSeleccionada = [];
                            },5000);

                        }
                        if(response.data.exito){
                            me.exitoCargaMasiva = response.data.exito;
                            setTimeout(function(){
                                me.exitoCargaMasiva = '';
                                me.departamento = '';
                                me.arrayAgenciasSeleccionada = [];
                            },5000);
                        }
                }).catch(function(error){
                    console.log("Error en listAgencia");
                });
            },
            //listamos todas la agencias existentes
            listAgencia(){
                var url = this.ruta + '/upLoad/ListAgencias';
                let me = this;
                axios.get(url).then(function(response){
                    me.arrayAgencias = response.data.agencia;
                }).catch(function(error){
                    console.log("Error en listAgencia");
                });
            },
            //metodo que valida los departamentos que no vallan nada vacio
            validarDepto(){
                this.erroresAg = 0;
                this.errorArraAg =[];
                if(!this.departamento) this.errorArraAg.push('ingrese el departamento');
                if(!this.arrayAgenciasSeleccionada.length) this.errorArraAg.push('seleccione alguna Agencias');

                if(this.errorArraAg.length) this.erroresAg = 1;
                return this.erroresAg;
            },
            validarCampos(){
                this.errores = 0;
                this.erroresArray = [];
                if(!this.tipoCarga) this.erroresArray.push('Ingrese tipo de Carga')
                if(!this.file) this.erroresArray.push('Subir Archivo')

                if(this.erroresArray.length) this.errores = 1
                return this.errores;
            }

        },
        mounted() {
            this.listAgencia();
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
