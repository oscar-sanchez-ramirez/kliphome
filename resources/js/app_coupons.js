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

//Coupons
Vue.component('newcoupon-component', require('./components/coupons/newCoupon.vue').default);
Vue.component('editcoupon-component', require('./components/coupons/editCoupon.vue').default);

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
});
