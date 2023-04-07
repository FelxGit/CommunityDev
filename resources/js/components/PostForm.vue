<template>
  <transition 
      enter-to-class="transform opacity-100 scale-100"
      leave-class="transform opacity-100 scale-100"
      id="postFormComponent">
    <div class="relative">
      <loading v-show="isLoadingCreatePost" :type="loader.type[0]" :withLoading="true"></loading>
      <div class="r-field-required grid gap-y-5">
        <div>
          <label class="grid gap-y-2">
            <span class="font-bold">{{ lang.get('words.Title') }} *</span>
            <input v-model.trim="$v.form.title.$model" class="h-14 rounded-lg bg-b-input border-b-input focus:placeholder:opacity-0" type="text" placeholder="Title"/>
          </label>
          <div class="errors">
            <error-input :name="'title'" :validations="['required', 'max.string', 'min.string']"></error-input>
          </div>
        </div>
        <div class="grid gap-2">
          <label class="grid gap-y-2"><span class="font-bold">{{ lang.get('words.SelectACategory') }} *</span></label>
          <multiselect v-model.trim="$v.form.category_id.$model"
          :options="categories"
          :custom-label="categoryOption"
          :allow-empty="false"
          trackBy="id"
          placeholder="Select one"
          selectedLabel="Selected"
          deselectLabel="Remove"
          openDirection=""
          :preselectFirst="true"
          :loading="_.isEmpty(categories)">
            <!-- <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template> -->
            <template slot="singleLabel" slot-scope="props">{{ categories[props.option-1].title }}</template>
            <template slot="option" slot-scope="props">{{ props.option.title }}</template>
          </multiselect>
          <div class="errors">
            <error-input :name="'category_id'" :validations="['required']"></error-input>
          </div>
        </div>
        <div class="grid gap-2">
          <label class="grid gap-y-2"><span class="font-bold">{{ lang.get('words.Tags') }}</span></label>
          <multiselect v-model.trim="$v.form.tags.$model"
          :multiple="true"
          :taggable="true"
          :options="tag_opt"
          :custom-label="tagOption"
          :allow-empty="false"
          :close-on-select="false"
          :clear-on-select="false"
          :limit="3"
          :limit-text="limitText"
          trackBy="id"
          placeholder="Search or add a tag"
          tag-placeholder="Add this as new tag"
          selectedLabel="Selected"
          deselectLabel="Remove"
          openDirection=""
          :preselectFirst="true"
          :loading="isLoadingTags"
          @tag="addTag">
            <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ form.tags.length }} options selected</span></template>
            <template slot="option" slot-scope="props">{{ props.option.title }}</template>
          </multiselect>
          <div class="errors">
            <error-input :name="'category_id'" :validations="['required']"></error-input>
          </div>
        </div>
        <div class="grid gap-2">
          <label class="grid gap-y-2">
            <span class="font-bold">{{ lang.get('words.PlainDescription') }}  *</span>
          </label>
          <textarea id="post" @change="assignTrumbowygDesc" placeholder="I am Trumbowyg"></textarea>
          <input type="hidden" v-model.trim="$v.form.plain_description.$model" />
          <div class="errors">
            <error-input :name="'plain_description'" :validations="['required']"></error-input>
          </div>
        </div>
      </div>
      <ui-button @click.prevent="submit" :type="'create'" :size="'full'" :withLoading="loading" :disabled="anyError" class="text-white mt-2">
        <span class="font-bold">{{ lang.get('words.Submit') }}</span>
      </ui-button>
    </div>
  </transition>
</template>
<script>
import Vue from "vue";
import { getters, mutations, actions } from "../store";
import { required, minLength, maxLength, sameAs, email, helpers } from 'vuelidate/lib/validators'

export default {
  props: {
    categories: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
      form: {
        title: '',
        plain_description: '',
        category_id: null,
        tags: []
      },
      tags: [],
      tag_opt: [],
      errors: {},
      isDirty: false,
      isLoadingCreatePost: false,
      isLoadingTags: false
    };
  },
  validations: {
    form: {
      title: {
        required, minLength: minLength(2), maxLength: maxLength(100)
      },
      plain_description: {
        required
      },
      category_id: {
        required
      },
      tags: {
      }
    }
  },
  created() {
    this.initData()
  },
  mounted() {
    console.log('mounted', this.trumbowyg)
    $.noConflict();

    $('#post').trumbowyg({
        lang: 'en',
        btnsDef: {
            image: {
                dropdown: ['insertImage', 'upload'],
                ico: 'insertImage'
            }
        },
        btns: this.trumbowyg.default.btns,
        plugins: {
            upload: {
                serverPath: window.location.href + 'api/posts/upload',
                fileFieldName: this.trumbowyg.upload_type.image,
                data: [ this.trumbowyg.uploadDataToken() ],
                urlPropertyName: 'url',
                headers: this.trumbowyg.default.plugins.upload.headers,
                error: function (response) {
                  console.log(response.data);
                  let alertData = {
                    showAlert: true,
                    type: "error",
                    message: response.responseJSON.errors,
                  };
                  console.log(alertData, 'trumbowyg.error()');
                  mutations.setAlert(alertData);
                }
            }
        }
    })
    .on('tbwchange', (event) => {
      this.form.plain_description = event.target.value;
    });
  },
  computed: {
    ...getters,
    inputTitle: function() { return this.$v.form.title.$error },
    inputPlainDescription: function() { return this.$v.form.plain_description.$error},
    categoryId: function() { return this.$v.form.category_id.$model },
    title: function({title}) { return title },
    isInvalid: function() {
      return _.values(this.form).some((v) => {
        return v == null || v == ''
      });
    },
    anyError: function() {
      return this.$v.form.$anyError || _.values(
        this._.omit(this.form, ['tags']) // except tags
      ).some((v) => v == null || v == '' );
    },
    formData: function() {
      let formData = this.form;
      return formData;
    }
  },
  methods: {
    ...mutations, ...actions,
    initData() {
      this.getTags()
    },
    getTags() {
      this.isLoadingTags = true;

      if (this._.isEmpty(this.tags))
        this.$http
          .get("api/tags")
          .then((response) => {
            if (_.has(response, "data.exception")) {
              let alertData = {
                showAlert: true,
                type: "error",
                message:
                  "Sorry our server has problem at the moment. Please come back later. Thank you.",
              };
              mutations.setAlert(alertData);
            } else {
              // SUCCESS
              this.tags = response.data;
            }
          })
          .catch(function (error) {
            console.log(error);
          })
          .finally(() => {
            this.isLoadingTags = false;
          });
    },
    submit() {
      this.isLoadingCreatePost = true;
      this.errors = null;

      this.$http.post('api/posts', this.formData)
      .then( (response) => {
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
            let alertData = {
              showAlert: true,
              type: "success",
              message:
                "Your post has been created.",
            };
            mutations.setAlert(alertData);
            this.$emit('initData')
            this.$emit('hidePostCreate')
          }
       })
      .finally(() => {
        this.isLoadingCreatePost = false;
        //
      })
    },
    categoryOption: function ({id, title}) {
      return title
    },
    tagOption: function ({id, title}) {
      return title
    },
    addTag (newTag) {
      const tag = {
        title: newTag[0] == '#'? newTag : '#' + newTag
      };
      this.tag_opt.push(tag)
      this.form.tags.push(tag)
    },
    limitText (count) {
      return `+ ${count} more`
    },
    assignTrumbowygDesc(e) {
      console.log('assignTrumbowygDesc', e.target.value);
      this.form.plain_description = e.target.value;
    }
  },
  watch: {
      inputTitle: {
          handler(value) {
            this.errors.title = null
          }
      },
      form: {
          handler(value) {
            if(!this.isDirty) {
              this.isDirty = true
            }
          },
          deep: true
      },
      categoryId: {
          handler(value) {
            if(value) {
              this.$v.form.category_id.$model = this._.get(value, 'id', value);
              this.form.category_id = this._.get(value, 'id', value);
            }
          },
          deep: true
      },
      tags: {
        handler() {
          this.tag_opt = this.tags;
        },
        deep: true
      },
      immediate: true,
      deep: true
  }
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped lang="scss">
@import '../../sass/imports';
  .border-b-input {
    border: thin solid $brand-bg-input;
  }

</style>