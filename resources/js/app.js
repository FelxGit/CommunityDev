require('./bootstrap')

import jQuery from 'jquery'
import Vue from 'vue'
import App from './pages/App'
import lang from './config/lang.js'
import axios from './config/axios.js'
import router from './config/routes.js'
import moment from 'moment'
import vuelidate from 'vuelidate'
// import trumbowyg from 'trumbowyg'

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
        let user = localStorage['chronoknowledge.user']? JSON.parse(localStorage['chronoknowledge.user']) : null;

        if(user) {
          mutations.setUser(user)
          mutations.setIsLoggedIn(true)
        }

        this.$http.get('api/language')
        .then( response => {
            console.log(response.data);
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