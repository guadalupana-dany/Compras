


require('./bootstrap');
window.Vue = require('vue');

//aqui importamos todos los componentes de vue.js
Vue.component('principal', require('./components/principal.vue'));
Vue.component('usuario', require('./components/User.vue'));
Vue.component('rol', require('./components/Rol.vue'));
Vue.component('solici', require('./components/Solicitante.vue'));
Vue.component('carga', require('./components/Cargas.vue'));
Vue.component('catalogos', require('./components/Catalogos.vue'));
Vue.component('mostrarsolicitud', require('./components/mostrarsolicitud.vue'));
Vue.component('reporteconta', require('./components/reporteConta.vue'));
Vue.component('productobodega', require('./components/productobodega.vue'));
Vue.component('detallesolicitud', require('./components/detallesolicitud.vue'));
Vue.component('solicitudesall', require('./components/solicitudesAll.vue'));
Vue.component('solicitudesrechazadas', require('./components/solicitudesrechazadas.vue'));
Vue.component('requisicioncliente', require('./components/requisicioncliente.vue'));

//requisicioncliente

const app = new Vue({
    el: '#app',
    data :{
        menu : 0,
       // ruta : 'http://10.60.81.32:81/sisPlanilla/public'
        ruta : 'http://10.60.81.31/Compras/public'
    }

});
