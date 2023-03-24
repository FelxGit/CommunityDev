require('./bootstrap')

import Vue from 'vue'
import App from './pages/App'
import lang from './config/lang.js'
import axios from './config/axios.js'
import router from './config/routes.js'
import moment from 'moment'
import CKEditor from '@ckeditor/ckeditor5-vue2'
import vuelidate from 'vuelidate'

import { getters, mutations, actions } from "./store";

Vue.use(router)
Vue.use(vuelidate)
Vue.use(CKEditor);
Vue.prototype.$http = axios
Vue.prototype.moment = moment

const app = new Vue({
    el: '#app',
    components: { App },
    router,
    validations:{},
    created: function () {
        mutations.setLoading(true)
        let user = localStorage['chronoknowledge.user']? JSON.parse(localStorage['chronoknowledge.user']) : null;
        const urlParams = new URLSearchParams(window.location.search);
        const param_userId = urlParams.get('user') ? urlParams.get('user') : null;
        const param_token = urlParams.get('token') ? urlParams.get('token') : null;

        if(user) {
          mutations.setUser(user)
          mutations.setIsLoggedIn(true)
        } else if (param_userId) {

          this.$http.get('api/users/' + param_userId)
          .then( function (response) {

            localStorage.setItem('chronoknowledge.jwt', JSON.stringify(param_token));
            localStorage.setItem('chronoknowledge.user', JSON.stringify(response.data));
            mutations.setUser(response.data);
            mutations.setIsLoggedIn(true)
          })
          .catch( function (error) {
            console.log(error);
          })
        }

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

            if (to.matched.some(record => record.meta.requiresAuth)) {
              // this route requires auth, check if logged in
              // if not, redirect to login page.
              if (this.user)
                next()
              else
                next({ name: 'login' })

            } else {
              let noAuthExcept = (to.name == 'login' || to.name == 'register') && !this._.isEmpty(this.user);

              if (noAuthExcept)
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