window.Vue = require('vue');
require('./bootstrap');
import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import Vuetify from 'vuetify'
// import * as VeeValidate from 'vee-validate';
Vue.use(Vuetify, {
    primary: '#ca90f4',
});
Vue.use(BootstrapVue);
// Vue.use(VeeValidate);
import store from './store_fixerman'

//Fixerman
Vue.component('pagos_fecha-component', require('./components/pagos/pagos_fecha.vue').default);


const app = new Vue({
    el: '#app',
    store,
    vuetify: new Vuetify(),
});
