require('./bootstrap');

window.Vue = require('vue');

window.events = new Vue();

window.flash = function (message) {
    window.events.$emit('flash', message);
};

Vue.component('question', require('./components/Question').default);
Vue.component('answer', require('./components/Answer').default);
Vue.component('flash', require('./components/Flash').default);
Vue.component('comments', require('./components/Comments').default);
const app = new Vue({
    el: '#app',
});
