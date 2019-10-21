import Vue from 'vue';
import VeeValidate from 'vee-validate';
import VueIziToast from 'vue-izitoast';
import 'izitoast/dist/css/iziToast.min.css';

Vue.use(VueIziToast);
Vue.use(VeeValidate);

Vue.component('dashboard', require('./components/DashboardComponent.vue').default);
Vue.component('group-component', require('./components/GroupComponent.vue').default);

window.SesCompanion = new Vue();