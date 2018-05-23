require('./bootstrap');

window.Vue = require('vue');

import Buefy from 'buefy'

Vue.component('slugWidget', require('./components/slugWidget.vue'));

Vue.use(Buefy)

import Echo from "laravel-echo"

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001'
});
