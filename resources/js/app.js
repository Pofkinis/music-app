require('./bootstrap');

import Vue from 'vue';
import Hello from './components/hello.vue';
const vue = new Vue({
    el: '#app',

    components:{
        'hello': Hello,
    }
});
