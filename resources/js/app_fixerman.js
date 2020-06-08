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
Vue.component('fixerman-component', require('./components/fixerman/FixermanComponent.vue').default);
Vue.component('calendar-component', require('./components/fixerman/calendar.vue').default);
Vue.component('stats-component', require('./components/fixerman/stats.vue').default);
Vue.component('categories-component', require('./components/fixerman/categories.vue').default);
Vue.component('reviews-component', require('./components/fixerman/reviews.vue').default);
Vue.component('payments-component', require('./components/fixerman/payments.vue').default);
Vue.component('info-component', require('./components/fixerman/info.vue').default);

const app = new Vue({
    el: '#app',
    store,
    vuetify: new Vuetify(),
});
