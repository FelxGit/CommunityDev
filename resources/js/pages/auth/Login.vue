<template>
  <div id="formComponent" class="form-component">
    <form
      class="h-auto w-[600px] m-auto bg-bt-secondary shadow-gray-900 shadow-xl relative rounded-md border border-white"
    >
      <div v-show="loading" class="absolute w-full h-full bg-form-overlay"></div>
      <div class="login-social m-10">
        <h3 class="text-center font-bold">
          <span class="text-b-info">{{ lang.get("words.Chronostep") }}</span>
          <span class="text-b-create">{{ lang.get("words.Community") }}</span>
        </h3>
        <p class="text-center">{{ lang.get("words.WelcomeToChronoCommunity") }}</p>
        <div class="grid justify-center grid grid-cols-1 gap-6 my-10">
          <button
            @click="loginWithFacebook($event)"
            type="button"
            class="h-14 w-full text-b-create bg-white border-2 border-b-create rounded-md"
          >
            <i class="fa-brands fa-facebook mr-3 text-lg"></i
            >{{ lang.get("words.ContinueWithFacebook") }}
          </button>
          <button
            @click="loginWithGoogle($event)"
            type="button"
            class="h-14 w-full text-b-info bg-white border-2 border-b-info rounded-md"
          >
            <i class="fa-brands fa-google mr-3 text-lg"></i
            >{{ lang.get("words.ContinueWithGoogle") }}
          </button>
        </div>
        <p class="horizontal-line text-center">
          <span class="horizontal-line-text p-0">
            <span class="text-b-mute">{{ lang.get("words.ContinueWithEmail") }}</span>
          </span>
        </p>
      </div>
      <div class="login-form r-field flex flex-col gap-6 m-10">
        <div class="r-field-required grid gap-y-5">
          <div>
            <label class="grid gap-y-2">
              <span class="font-bold">{{ lang.get("words.Email") }}</span>
              <input
                v-model="$v.form.email.$model"
                class="h-14 w-full rounded-lg bg-b-input border-b-input focus:placeholder:opacity-0"
                type="email"
                placeholder="E-mail"
              />
            </label>
            <error-input :name="'email'" :validations="['required', 'email', 'min.string', 'max.string']"></error-input>
          </div>
          <div>
            <label class="grid gap-y-2">
              <span class="font-bold">{{ lang.get("words.Password") }}</span>
              <input
                v-model="$v.form.password.$model"
                class="h-14 w-full rounded-lg bg-b-input border-b-input focus:placeholder:opacity-0"
                type="password"
                placeholder="Password"
              />
            </label>
            <error-input :name="'password'" :validations="['required', 'min.string', 'max.string']"></error-input>
          </div>
        </div>
        <div class="grid sm:grid-cols-1 gap-5">
          <div>
            <label class="flex flex-flow gap-6 content-center">
              <input
                type="checkbox"
                v-model="$v.form.remember.$model"
                class="my-1"
                :checked="true"
              />
              <span class="text-b-mute">{{ lang.get("words.RememberMe") }}</span>
            </label>
            <error-input :name="'rememberMe'"></error-input>
          </div>
        </div>
        <ui-button
          @click.prevent="submit"
          :type="'create'"
          :size="'full'"
          :withLoading="loading"
          :disabled="$v.form.$anyError || !$v.form.$anyDirty || isInvalid"
          class="text-white"
        >
          <span class="font-bold">{{ lang.get("words.Continue") }}</span>
        </ui-button>
        <router-link :to="{ name: 'reset-password'}" :type="'sendEmail'" class="text-center text-b-link">Forgot Password?</router-link>
      </div>
    </form>
  </div>
</template>
<script>
import Vue from "vue";
import { getters, mutations, actions } from "../../store";
import { required, minLength, maxLength, sameAs, email, helpers } from "vuelidate/lib/validators";

export default {
  data() {
    return {
      form: {
        email: "",
        password: "",
        remember: false,
      },
      errors: {},
    };
  },
  validations: {
    form: {
      email: {
        required,
        email,
        minLength: minLength(4),
        maxLength: maxLength(50)
      },
      password: {
        required,
        minLength: minLength(4),
        maxLength: maxLength(60)
      },
      remember: {
        // checked(val) {
        //   return val == true;
        // }
      },
    },
  },
  mounted() {
    this.setLogin();
  },
  computed: {
    ...getters,
    inputEmail: function () {
      return this.$v.form.email.$error;
    },
    inputPassword: function () {
      return this.$v.form.password.$error;
    },
    // inputRememberMe: function() { return this.$v.form.remember.$error },
    isInvalid: function () {
      return _.values(this.form).some((v) => {
        return v === "";
      });
    },
  },
  methods: {
    ...mutations,
    ...actions,
    loginWithGoogle() {
      let win = window.open(this.appConfig.SERVER_URL + '/api/auth/google', "SignIn", "width=780,height=410,toolbar=0,scrollbars=0,status=0,resizable=0,location=0,menuBar=0,left=" + 500 + ",top=" + 200);
      var timer = setInterval(function() {
          if(win.closed) {
              clearInterval(timer);
              window.location = '/'
          }
      }, 1000);
    },
    loginWithFacebook() {
      let win = window.open(this.appConfig.SERVER_URL + '/api/auth/facebook', "SignIn", "width=780,height=410,toolbar=0,scrollbars=0,status=0,resizable=0,location=0,menuBar=0,left=" + 500 + ",top=" + 200);
      var timer = setInterval(function() {
          if(win.closed) {
              clearInterval(timer);
              window.location = '/'
          }
      }, 1000);
      // window.location.replace('/api/auth/facebook')

      // facebook creates pop up window for login
      // works only for https
      // FB.login(function(response) {
      //     if (response.authResponse) {
      //     console.log('Welcome!  Fetching your information.... ');
      //     FB.api('/api/auth/facebook', function(response) {
      //       console.log('Good to see you, ' + response.name + '.');
      //     });
      //     } else {
      //     console.log('User cancelled login or did not fully authorize.');
      //     }
      // });
    },
    submit() {
      mutations.setLoading(true);
      this.errors = null;
      let formData = this.form;

      this.$http
        .post("api/login", formData)
        .then((response) => {
          if (_.has(response, "data.errors")) {
            this.errors = response.data.errors;
          } else if (_.has(response, "data.exception")) {
            let alertData = {
              showAlert: true,
              type: "error",
              message:
                "Sorry our server has problem at the moment. Please come back later. Thank you.",
            };
            mutations.setAlert(alertData);
          } else {
            // SUCCESS
            mutations.setUser(response.data.user);
            mutations.setIsLoggedIn(true);

            if (this.form.remember) {
              localStorage.setItem("remember_user", JSON.stringify(this.form));
            } else if (localStorage["remember_user"] && !this.form.remember) {
              localStorage.removeItem("remember_user");
            }

            localStorage.setItem(
              "chronoknowledge.jwt",
              JSON.stringify(response.data.token)
            );
            localStorage.setItem(
              "chronoknowledge.user",
              JSON.stringify(response.data.user)
            );
            this.$router.push("/");
          }
        })
        .catch(function (error) {
          console.log(error);
        })
        .finally(() => {
          mutations.setLoading(false);
        });
    },
    setLogin() {
      let remember_user = localStorage["remember_user"]
        ? JSON.parse(localStorage["remember_user"])
        : null;
      if (remember_user) {
        this.form = remember_user;
      }
    },
  },
  watch: {
    inputEmail: {
      handler(value) {
        this.errors.email = null;
      },
    },
    inputPassword: {
      handler(value) {
        this.errors.password = null;
      },
    },
    // inputRememberMe: {
    //     handler(value) {
    //       this.errors.remember = false
    //     }
    // },
    immediate: true,
    deep: true,
  },
};
</script>
<style scoped lang="scss">
@import "../../../sass/imports";
.form-component {
  display: grid;
    grid-template-columns: 1fr;
    justify-content: center;
    align-items: center;
    grid-gap: $base-gap;
    margin: 6rem auto;
}

@media screen and (max-width: 1200px) {
  .form-component {
    margin: $base-gap;

    form {
      border: none!important;
      background-color: transparent;
      width: 100%;
      box-shadow: none;

      .login-form.m-10, .login-social.m-10 {
        margin: 0;
      }
    }
  }
}
.border-b-input {
  border: thin solid $brand-bg-input;
}
</style>
