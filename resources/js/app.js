
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vendor components
import { Modal, VoerroModal } from '@voerro/vue-modal';

Vue.component('v-modal', Modal);
window.VoerroModal = VoerroModal;

// Local components
Vue.component('v-profile-threads', require('./components/profile/Threads.vue').default);
Vue.component('v-threads-page', require('./components/threads/Page.vue').default);
Vue.component('v-thread-replies', require('./components/threads/Replies.vue').default);
Vue.component('v-admin-threads', require('./components/admin/Threads.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});
