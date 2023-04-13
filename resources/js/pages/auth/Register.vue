<template>
  <div id="formComponent" class="form-component">
    <form class="h-auto w-[600px] m-auto bg-bt-secondary shadow-gray-900 shadow-xl relative rounded-md border border-white">
      <div v-show="loading" class="absolute w-full h-full bg-form-overlay"></div>
      <div class="login-social m-10">
        <h3 class="text-center font-bold">
          <span class="text-b-info">{{ lang.get('words.Chronostep') }}</span> <span class="text-b-create">{{ lang.get('words.Community') }}</span>
        </h3>
        <p class="text-center">{{ lang.get('words.WelcomeToChronoCommunity') }}</p>
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
          <span class="horizontal-line-text">
            <span class="text-b-mute">{{ lang.get('words.HaveAccount') }}</span> 
            <router-link :to="{ name: 'login' }" class="text-b-info">{{ lang.get('words.LogIn.') }}</router-link>
          </span>
        </p>
      </div>
      <div class="login-form r-field flex flex-col gap-10 m-10">
        <div class="r-field-required grid gap-y-5">
          <div>
            <label class="grid gap-y-2">
              <span class="font-bold">{{ lang.get('words.FirstName') }}</span>
              <input
                v-model.trim="$v.form.first_name.$model"
                @input="(val) => (form.first_name = _.startCase(_.toLower(form.first_name)))"
                class="h-14 w-full rounded-lg bg-b-input border-b-input focus:placeholder:opacity-0" type="text" placeholder="First Name"/>
            </label>
            <div class="errors">
              <error-input :name="'first_name'" :validations="['required', 'min.string', 'max.string']"></error-input>
            </div>
          </div>
          <div>
            <label class="grid gap-y-2">
              <span class="font-bold">{{ lang.get('words.MiddleName') }} <span class="text-b-mute">(optional)</span></span>
              <input
                v-model.trim="$v.form.middle_name.$model"
                @input="(val) => (form.middle_name = _.startCase(_.toLower(form.middle_name)))"
                class="h-14 w-full rounded-lg bg-b-input border-b-input focus:placeholder:opacity-0" type="text" placeholder="Middle Name"/>
            </label>
            <div class="errors">
              <error-input :name="'middle_name'" :validations="['min.string', 'max.string']"></error-input>
            </div>
          </div>
          <div>
            <label class="grid gap-y-2">
              <span class="font-bold">{{ lang.get('words.LastName') }}</span>
              <input
                v-model.trim="$v.form.last_name.$model"
                @input="(val) => (form.last_name = _.startCase(_.toLower(form.last_name)))"
                class="h-14 w-full rounded-lg bg-b-input border-b-input focus:placeholder:opacity-0" type="text" placeholder="Last Name"/>
            </label>
            <div class="errors">
              <error-input :name="'last_name'" :validations="['required', 'min.string', 'max.string']"></error-input>
            </div>
          </div>
          <div class="grid sm:grid-cols-2 gap-5">
            <div>
              <label class="grid gap-y-2">
                <span class="font-bold">{{ lang.get('words.Username') }}</span>
                <input
                  v-model="$v.form.username.$model"
                  @input="(val) => (form.username = _.startCase(_.toLower(form.username)))"
                  class="h-14 w-full bg-b-input rounded-lg border-b-input focus:placeholder:opacity-0" type="text" placeholder="Username"/>
              </label>
              <error-input :name="'username'" :validations="['required', 'min.string', 'max.string']"></error-input>
            </div>
            <div>
              <label class="grid gap-y-2">
                <span class="font-bold">{{ lang.get('words.Nickname') }}</span>
                <input
                  v-model="$v.form.nick_name.$model"
                  @input="(val) => (form.nick_name = _.startCase(_.toLower(form.nick_name)))"
                  class="h-14 w-full bg-b-input rounded-lg border-b-input focus:placeholder:opacity-0" type="text" placeholder="Nickname"/>
              </label>
              <error-input :name="'nick_name'" :validations="['min.string', 'max.string']"></error-input>
            </div>
          </div>
          <div class="grid sm:grid-cols-2 gap-5">
            <div>
              <label class="grid gap-y-2">
                <span class="font-bold">{{ lang.get('words.Gender') }}</span>
                <select v-model="$v.form.gender.$model" class="h-14 w-full bg-b-input rounded-lg border-b-input focus:placeholder:opacity-0">
                  <option value="0" :selected="$v.form.gender.$model == '0'">{{ lang.get('words.Male') }}</option>
                  <option value="1" :selected="$v.form.gender.$model == '1'">{{ lang.get('words.Female') }}</option>
                </select>
              </label>
              <error-input :name="'gender'" :validations="['required']"></error-input>
            </div>
            <div>
              <label class="grid gap-y-2">
                <span class="font-bold">{{ lang.get('words.DateOfBirth') }}</span>
                <input v-model="$v.form.birth_date.$model" class="h-14 w-full bg-b-input rounded-lg border-b-input focus:placeholder:opacity-0" type="date"/>
              </label>
              <error-input :name="'birth_date'" :validations="['required', 'date']"></error-input>
            </div>
          </div>
          <div class="grid sm:grid-cols-2 gap-5">
            <div>
              <label class="grid gap-y-2">
                <span class="font-bold">{{ lang.get('words.Telephone') }}</span>
                <input v-model="$v.form.tel.$model" class="h-14 w-full rounded-lg bg-b-input border-b-input focus:placeholder:opacity-0" type="tel" placeholder="Telephone"/>
              </label>
              <error-input :name="'tel'" :validations="['required', 'min.string', 'max.string']"></error-input>
            </div>
            <div>
              <label class="grid gap-y-2">
                <span class="font-bold">{{ lang.get('words.Zipcode') }}</span>
                <input v-model="$v.form.zip_code.$model" class="h-14 w-full rounded-lg bg-b-input focus:placeholder:opacity-0" type="text" pattern="[0-9]{5}" placeholder="Zip Code" />
              </label>
              <error-input :name="'zip_code'" :validations="['required', 'max.string']"></error-input>
            </div>
          </div>
          <div class="grid sm:grid-cols-1 gap-5">
            <div>
              <label class="grid gap-y-2">
                <span class="font-bold">{{ lang.get('words.Address') }}</span>
                <input v-model="$v.form.address.$model" class="h-14 w-full rounded-lg bg-b-input border-b-input focus:placeholder:opacity-0" type="text" placeholder="Address"/>
              </label>
              <error-input :name="'address'" :validations="['min.string', 'max.string']"></error-input>
            </div>
            <div>
              <label class="grid gap-y-2">
                <span class="font-bold">{{ lang.get('words.Email') }}</span>
                <input v-model="$v.form.email.$model" class="h-14 w-full rounded-lg bg-b-input border-b-input focus:placeholder:opacity-0" type="email" placeholder="E-mail"/>
              </label>
              <error-input :name="'email'" :validations="['required', 'email', 'min.string', 'max.string']"></error-input>
            </div>
            <div>
              <label class="grid gap-y-2">
                <span class="font-bold">{{ lang.get('words.Password') }}</span>
                <input v-model="$v.form.password.$model" class="h-14 w-full rounded-lg bg-b-input border-b-input focus:placeholder:opacity-0" type="password" placeholder="Password"/>
              </label>
              <error-input :name="'password'" :validations="['required', 'min.string', 'max.string']"></error-input>
            </div>
            <div>
              <label class="grid gap-y-2">
                <span class="font-bold">{{ lang.get('words.PasswordConfirmation') }}</span>
                <input v-model="$v.form.password_confirmation.$model" class="h-14 w-full rounded-lg bg-b-input border-b-input focus:placeholder:opacity-0" type="password" placeholder="Confirm Password"/>
              </label>
              <error-input :name="'password_confirmation'" :validations="['required', 'same']"></error-input>
            </div>
          </div>
        </div>
        <div class="grid sm:grid-cols-1 gap-5">
          <div>
              <label class="flex flex-flow gap-6 content-center">
                <input type="checkbox" v-model="$v.form.termsAndPrivacy.$model" class="my-1"/>
                <span class="text-b-mute">{{ lang.get('words.TermsAndPrivacy') }}</span>
              </label>
              <error-input :name="'termsAndPrivacy'" :validations="['accepted']"></error-input>
            </div>
        </div>
        <ui-button @click.prevent="submit" :type="'create'" :size="'full'" :withLoading="loading" :disabled="anyError" class="text-white">
          <span class="font-bold">{{ lang.get('words.Continue') }}</span>
        </ui-button>
      </div>
    </form>
  </div>
</template>
<script>
import Vue from "vue";
import { getters, mutations, actions } from "../../store";
import { required, minLength, maxLength, sameAs, email, helpers } from 'vuelidate/lib/validators'

export default {
  data() {
    return {
      form: {
        first_name: '',
        middle_name: '',
        last_name: '',
        email: '',
        username: '',
        password: '',
        password_confirmation: '',
        address: '',
        zip_code: '',
        tel: '',
        nick_name: '',
        birth_date: '',
        gender: 0,
        termsAndPrivacy: false
      },
      errors: {}
    };
  },
  validations: {
    form: {
      first_name: {
        required, minLength: minLength(2), maxLength: maxLength(16)
      },
      middle_name: {
        minLength: minLength(2), maxLength: maxLength(16)
      },
      last_name: {
        required, minLength: minLength(2), maxLength: maxLength(16)
      },
      email: {
        required, email, minLength: minLength(4), maxLength: maxLength(50)
      },
      username: {
        required, minLength: minLength(4), maxLength: maxLength(50)
      },
      password: {
        required, minLength: minLength(4), maxLength: maxLength(60),
      },
      password_confirmation: {
        required
        // sameAs: function(val) {
        //   // console.log('password_confirmation', val, this.form)
        // }
      },
      address: {
        minLength: minLength(4), maxLength: maxLength(100)
      },
      zip_code: {
        required, maxLength: maxLength(8)
      },
      tel: {
        required, minLength: minLength(11), maxLength: maxLength(11)
      },
      nick_name: {
        minLength: minLength(4), maxLength: maxLength(20)
      },
      birth_date: {
        required
        // isDate: function(birth_date) {
        //   return moment(birth_date).isValid()
        // }
      },
      gender: {
        required
      },
      termsAndPrivacy: {
        accepted: (val) => {
          return val;
        }
      }
    }
  },
  mounted () {
    // 
  },
  computed: {
    ...getters,
    inputFirstName: function() { return this.$v.form.first_name.$error },
    inputMiddleName: function() { return this.$v.form.first_name.$error },
    inputLastName: function() { return this.$v.form.first_name.$error },
    inputEmail: function() { return this.$v.form.email.$error },
    inputUsername: function() { return this.$v.form.username.$error },
    inputPassword: function() { return this.$v.form.password.$error },
    inputPasswordC: function() { return this.$v.form.password_confirmation.$error },
    inputAddress: function() { return this.$v.form.address.$error },
    inputZipCode: function() { return this.$v.form.zip_code.$error },
    inputTel: function() { return this.$v.form.tel.$error },
    inputNickname: function() { return this.$v.form.nick_name.$error },
    inputBirthdate: function() { return this.$v.form.birth_date.$error },
    inputGender: function() { return this.$v.form.gender.$error },
    inputTermsAndPrivacy: function() { return this.$v.form.termsAndPrivacy.$error },

    formData: function() {
      let formData = {}
      let name = ''

      if(this.form.first_name && this.form.middle_name && this.form.last_name) {
        name = this.form.first_name + ' ' + this.form.middle_name + ' ' + this.form.last_name;
      }
      else if(this.form.first_name && this.form.last_name) {
        name = this.form.first_name + ' ' + this.form.last_name;
      }
      this.form.name = name

      return this.form
    },
    isInvalid: function() {
      return _.values(this.form).some((v) => {
        return v === '' || v === false || this.form.password != this.form.password_confirmation
      });
    },
    anyError: function() {
      return this.$v.form.$anyError || !this.$v.form.$anyDirty || this.isInvalid
    }
  },
  methods: {
    ...mutations,
    ...actions,
    submit() {
      mutations.setLoading(true);
      this.errors = null;
      let formData = this.formData

      this.$http.post('api/register', formData)
      .then( (response) => {
          if(_.has(response, 'data.errors')) {
            this.errors = response.data.errors;
          } else {
            // SUCCESS
            let alertData = {
              showAlert: true,
              type: 'success',
              message: 'Account is now formed.'
            };
            mutations.setAlert(alertData)
            this.$router.push('login')
          }
       })
      .finally(() => {
        mutations.setLoading(false)
        //
      })
    },
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
    },
  },
  watch: {
      inputName: {
          handler(value) {
            this.errors.name = null
          }
      },
      inputEmail: {
          handler(value) {
            this.errors.email = null
          }
      },
      inputUsername: {
          handler(value) {
            this.errors.username = null
          }
      },
      inputPassword: {
          handler(value) {
            this.errors.password = null
          }
      },
      inputPasswordC: {
          handler(value) {
            this.errors.password_confirmation = null
          }
      },
      inputAddress: {
          handler(value) {
            this.errors.address = null
          }
      },
      inputZipCode: {
          handler(value) {
            this.errors.zip_code = null
          }
      },
      inputTel: {
          handler(value) {
            this.errors.tel = null
          }
      },
      inputBirthdate: {
          handler(value) {
            this.errors.birth_date = null
          }
      },
      inputGender: {
          handler(value) {
            this.errors.gender = null
          }
      },
      inputTermsAndPrivacy: {
          handler(value) {
            this.errors.termsAndPrivacy = false
          }
      },
      immediate: true,
      deep: true
  }
};
</script>
<style scoped lang="scss">
@import '../../../sass/imports';
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
    .horizontal-line-text {
      padding: 0
    }
  }
  .border-b-input {
    border: thin solid $brand-bg-input;
  }
</style>