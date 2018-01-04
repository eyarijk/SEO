require('./bootstrap');

window.Vue = require('vue');

import Buefy from 'buefy'

Vue.component('slugWidget', require('./components/slugWidget.vue'));

Vue.use(Buefy);
