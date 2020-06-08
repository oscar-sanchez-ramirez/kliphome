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
import store from './store'

Vue.component('contact-form-component', require('./components/ContactFormComponent.vue').default);
Vue.component('message-conversation-component', require('./components/MessageConversationComponent.vue').default);
Vue.component('contact-component', require('./components/ContactComponent.vue').default);
Vue.component('contact-list-component', require('./components/ContactListComponent.vue').default);
Vue.component('active-conversation-component', require('./components/ActiveConversationComponent.vue').default);
Vue.component('messenger-component', require('./components/MessengerComponent.vue').default);
Vue.component('status-component', require('./components/StatusComponent.vue').default);
//Fixerman
Vue.component('fixerman-component', require('./components/fixerman/FixermanComponent.vue').default);
Vue.component('calendar-component', require('./components/fixerman/calendar.vue').default);
Vue.component('stats-component', require('./components/fixerman/stats.vue').default);
Vue.component('categories-component', require('./components/fixerman/categories.vue').default);
Vue.component('reviews-component', require('./components/fixerman/reviews.vue').default);

const app = new Vue({
    el: '#app',
    store,
    vuetify: new Vuetify(),
});
