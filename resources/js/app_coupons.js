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

//Fixerman
// Vue.component('coupon-component', require('./components/coupons/couponComponent.vue').default);
Vue.component('newcoupon-component', require('./components/coupons/newCoupon.vue').default);
Vue.component('editcoupon-component', require('./components/coupons/editCoupon.vue').default);
// Vue.component('stats-component', require('./components/fixerman/stats.vue').default);
// Vue.component('categories-component', require('./components/fixerman/categories.vue').default);
// Vue.component('reviews-component', require('./components/fixerman/reviews.vue').default);
// Vue.component('payments-component', require('./components/fixerman/payments.vue').default);
// Vue.component('info-component', require('./components/fixerman/info.vue').default);

const app = new Vue({
    el: '#app',
    // store,
    vuetify: new Vuetify(),
});
