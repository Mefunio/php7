import {StudentsRepository} from "./api/StudentsRepository";

require('./bootstrap');
import Spa from './Spa.vue';
import router from './router.js';
import Vue from 'vue';
import {Auth} from './api/Auth';

window.Vue = require('vue');

const app = new Vue({
    el: '#app',
    render: h => h(Spa),
    router,
    data: {
        eventBus: new Vue()
    },
    provide() {
        return {
            eventBus: this.eventBus,
            auth: new Auth(this.eventBus, router),
            studentsRepository: new StudentsRepository(this.eventBus)
        };
    }
});
