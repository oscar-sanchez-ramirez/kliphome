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
import store from './store_order'

//Fixerman
Vue.component('order-component', require('./components/orders/OrderComponent.vue').default);
Vue.component('detail-component', require('./components/orders/details.vue').default);
Vue.component('fixerman-component', require('./components/orders/fixerman.vue').default);
Vue.component('payments-component', require('./components/orders/payments.vue').default);
Vue.component('quotations-component', require('./components/orders/quotations.vue').default);
Vue.component('configuration-component', require('./components/orders/configuration.vue').default);
Vue.component('create-order-component', require('./components/orders/createorder.vue').default);
Vue.component('qualifies-component', require('./components/orders/qualifies.vue').default);
Vue.component('comments-component', require('./components/orders/comments.vue').default);
const app = new Vue({
    el: '#app',
    store,
    vuetify: new Vuetify(),
});
