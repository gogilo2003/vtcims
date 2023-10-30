window._ = require('lodash');

window.axios = require('axios');
// window.jquery = require('jquery');
// window.cropper = require('cropper');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import axios from 'axios';
import Vue from 'vue'
import store from './store'

//Main pages
import Fees from './views/Fees.vue'
import FeeTransactions from './views/FeeTransactions.vue'
import VoteHeads from './views/VoteHeads.vue'
import Grading from './views/Grading.vue'

// Vue.component('comment', Comment);

axios.defaults.headers['Accept'] = 'application/json'

const app = new Vue({
    el: '#app',
    components: { Fees, FeeTransactions, VoteHeads, Grading },
    store
});