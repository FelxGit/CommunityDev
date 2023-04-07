require('./bootstrap')

import Vue from 'vue'
import App from './pages/App'
import lang from './config/lang.js'
import axios from './config/axios.js'
import router from './config/routes.js'
import moment from 'moment'
import vuelidate from 'vuelidate'

import { getters, mutations, actions } from "./store";

Vue.use(router)
Vue.use(vuelidate)
Vue.prototype.$http = axios
Vue.prototype.moment = moment

const app = new Vue({
    el: '#app',
    components: { App },
    router,
    validations:{},
    created: function () {

        mutations.setLoading(true)

        // Auth
        let user = localStorage['chronoknowledge.user']? JSON.parse(localStorage['chronoknowledge.user']) : null;
        const urlParams = new URLSearchParams(window.location.search);
        const param_userId = urlParams.get('user') ? urlParams.get('user') : null;
        const param_token = urlParams.get('token') ? urlParams.get('token') : null;

        if(user) {
          mutations.setUser(user)
          mutations.setIsLoggedIn(true)
        } else if (param_userId) {

          this.$http.get('api/users/' + param_userId)
          .then((response) => {

            localStorage.setItem('chronoknowledge.jwt', JSON.stringify(param_token));
            localStorage.setItem('chronoknowledge.user', JSON.stringify(response.data));

            // assuming current is login popup window
            window.close();
          })
          .catch( function (error) {
            console.log(error);
          })
        }

        // language

        this.$http.get('api/language')
        .then( response => {
            let source = {
                'en.words': response.data.messages,
                'en.auth': response.data.auth,
                'en.validation': response.data.validation
            }

            lang.setMessages(source)
            mutations.setLang(lang)
        })
        .finally(() => {
            mutations.setLoading(false)
        })

        router.beforeEach((to, from, next) => {
            // if route is set to require authentication, redirect to login
            if (to.matched.some(record => record.meta.requiresAuth)) {
              if (this.user)
                next()
              else
                next({ name: 'login' })

            } else {
              if (!this._.isEmpty(this.user) && to.name.includes(['login', 'register']))
                next({ name: 'landing-page' })
              else
                next()
            }
          })
    },
    computed: {
        ...getters
    },
    methods: {
        ...mutations, ...actions,
    }
})