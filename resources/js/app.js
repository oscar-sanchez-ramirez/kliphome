window.Vue = require('vue');
require('./bootstrap_fixerman');
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

const app = new Vue({
    el: '#app',
    store,
    vuetify: new Vuetify(),
});
