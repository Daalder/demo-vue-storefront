import getCustomerInfo from './services/getCustomerInfo';
import PrettyCheckbox from 'pretty-checkbox-vue';
import FormatsCurrency from "./mixins/FormatsCurrency";

const VueScrollTo = require('vue-scrollto');
require('./validationRules');

window._ = require('lodash');
window.$ = window.jQuery = require('jquery');
window.axios = require('axios');
window.Promise = require('es6-promise').Promise;
window.cookie = require('js-cookie');

window.axios.default.responseType = 'json';
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['X-Daalder-Store'] = process.env.MIX_STORE_ID;

window.Vue = require('vue');
Vue.use(PrettyCheckbox);
Vue.use(VueScrollTo);

window.collect = require('collect.js');
window.cache = {};
window.bus = new Vue;

getCustomerInfo();

const files = require.context('./', true, /\.vue$/i);
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

window.app = new Vue({
    el: '#app',
    data() {
        return {
            store: null,
            user: null
        }
    },
    mixins: [FormatsCurrency],
    mounted() {
        this.store = JSON.parse(this.$el.attributes.store.value);
        bus.$emit('store', this.store);

        this.user = JSON.parse(this.$el.attributes.user.value);
        bus.$emit('user', this.user);
    },
    methods: {
        openSideBar() {
            bus.$emit('open-sidebar');
        }
    }
});

// apply interceptor on response
axios.interceptors.response.use(
    response => response,
    function(error) {
        if (error.config && error.response && error.response.status === 403) {
            return getCustomerInfo(true)
                .then((data) => {
                    error.config.headers.common['X-Daalder-Customer-Token'] = data.customer_token;
                    return axios.request(error.config);
                });
        }
        return Promise.reject(error);
    }
);

