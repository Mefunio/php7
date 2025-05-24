require('./bootstrap');
import Spa from './Spa.vue';
import router from './router.js';
import Vue from 'vue';

window.Vue = require('vue');

const app = new Vue({
    el: '#app',
    render: h => h(Spa),
    router
});
