<template>
  <div class="post">
    <div>
      <div class="grid p-6 gap-6 rounded-lg break-all">
          <div class="flex gap-6">
              <div>
                  <img src="../../../assets/icons/user-logo.png" class="h-12 border border-white rounded-full"/>
              </div>
              <div class="grid">
                  <span class="text-b-info font-bold">{{ _.get(post.user, 'name', '') }}</span>
                  <span class="text-b-mute">{{ _.get(post, 'user.job', '') }}</span>
                  <span class="text-gray-700"><small>{{ moment(_.get(post, 'created_at', '')).format("MMM Do YYYY, h:mm A") }}</small></span>
              </div>
          </div>
          <div class="rounded-lg bg-bt-secondary px-6 py-4">
              <h3 class="font-bold">{{ _.get(post, 'title', '') }}</h3>
          </div>
          <div class="bg-bt-secondary rounded-lg p-6">{{ _.get(post, 'plain_description', '') }}</div>
          <div class="text-b-link">
              <span v-for="(tag, index) in post.tags" :key="index">{{ _.get(tag, 'title', '') }} </span>
          </div>
          <div class="flex gap-6">
              <span>
                <span>{{ abbreviateNumber(_.filter(_.get(post, 'likes', []), v => v.deleted_at == null).length) }}</span>
                <span @click="likeUnlikePost($event, post.likes, post.id)" :class="[{ 'text-b-link': isLikedByUser(post.likes) && isLikedNotDeleted(post.likes) }]" role="button">
                  <i class="fa-regular fa-thumbs-up pr-3"></i>{{ lang.get('words.Likes') }}
                </span>
              </span>
              <span @click="toggleDiscussionForm" role="button">
                <span>{{ abbreviateNumber(_.filter(_.get(post, 'comments', []), v => v.deleted_at == null).length) }}</span>
                <span>
                  <i class="fa-regular fa-comment pr-3"></i>
                  {{ lang.get("words.Comments") }}
                </span>
              </span>
              <span>
                <span >{{ abbreviateNumber(_.get(post, 'favorites', []).length) }}</span>
                <span @click="setAsFavorite($event, post.favorites, post.id)" :class="[{ 'text-amber-400': isFavoriteByUser(post.favorites) && isFavoriteNotDeleted(post.favorites) }]" role="button">
                  <i class="fa-regular fa-star pr-3"></i> {{ lang.get('words.Favorites') }}</span>
              </span>
          </div>
      </div>
      <div class="comment">
        <div v-show="showDiscussionForm">
          <h3>{{ `${lang.get('words.Discussions')}(${_.get(post,'comments', []).length})` }}</h3>
          <div class="relative">
            <div v-show="isLoadingComment" class="absolute w-full h-full bg-form-overlay z-10"></div>
            <form>
              <div class="grid gap-2">
                  <ckeditor :editor="CKEditor.editor" v-model.trim="$v.form.description.$model"></ckeditor>
              </div>
              <ui-button @click.prevent="submitComment" :type="'create'" :size="'full'" :withLoading="isLoadingComment" :disabled="anyError" class="text-white mt-2">
                <span class="font-bold">{{ lang.get('words.Submit') }}</span>
              </ui-button>
            </form>
          </div>
        </div>
        <div class="antialiased mx-auto">
          <h3 class="mb-4 text-lg font-semibold">{{ lang.get('words.Comments') }}</h3>

          <div class="space-y-4">
            <div v-for="(comment, key, index) in post.comments" :key="key" class="flex">
              <div class="flex-shrink-0 mr-3">
                <img class="mt-2 rounded-full w-8 h-8 sm:w-10 sm:h-10" src="../../../assets/icons/user-logo.png" alt="">
              </div>
              <div class="flex-1 bg-bt-secondary rounded-lg px-4 py-2 s
              +m:px-6 sm:py-4 leading-relaxed"> 
                <strong>{{ _.get(comment, 'user.name', '') }}</strong> <span class="text-xs text-gray-400">{{ moment(_.get(post, "created_at", "")).format("MMM Do YYYY, h:mm A") }}</span>
                <p class="text-sm break-all" v-html="_.get(comment, 'description', '')"></p>
                <div v-show="_.get(comment, 'replies', []).length > 0" class="mt-4 flex items-center">
                  <div class="flex -space-x-2 mr-2">
                    <img class="rounded-full w-6 h-6 border border-white" src="https://images.unsplash.com/photo-1554151228-14d9def656e4?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=100&h=100&q=80" alt="">
                    <img class="rounded-full w-6 h-6 border border-white" src="https://images.unsplash.com/photo-1513956589380-bad6acb9b9d4?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=100&h=100&q=80" alt="">
                  </div>
                  <div class="text-sm text-gray-500 font-semibold">
                    {{ _.get(comment, 'replies', []).length }} {{ lang.get('words.Replies') }}
                  </div>
                </div>
                <!-- <h4 class="my-5 uppercase tracking-wide text-gray-400 font-bold text-xs">Replies</h4>
                <div class="space-y-4">
                  <div v-for="(commentReply, key, index) in comments.replies" :key="key" class="flex">
                    <div class="flex-shrink-0 mr-3">
                      <img class="mt-3 rounded-full w-6 h-6 sm:w-8 sm:h-8" src="../../../assets/icons/user-logo.png" alt="">
                    </div>
                    <div class="flex-1 border rounded-lg px-4 py-2 sm:px-6 sm:py-4 leading-relaxed">
                      <strong>Sarah</strong> <span  class="text-xs text-gray-400">3:34 PM</span>
                      <p class="text-xs sm:text-sm">{{ _.get(commentReply, 'description')}}</p>
                    </div>
                  </div>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getters, mutations, actions } from "../../store";
import { required, minLength, maxLength, sameAs, email, helpers } from 'vuelidate/lib/validators'

export default {
    data() {
        return {
            post: null,
            isLoadingPost: false,
            isLoadingComment: false,
            showDiscussionForm: false,
            form: {
              description: ''
            },
            errors: []
        };
    },
    validations: {
    form: {
      description: {
        required
      }
    }
  },
    created() {
        this.SI_SYMBOL = ["", "k", "M", "G", "T", "P", "E"];
        this.initData()
    },
    methods: {
        ...mutations,...actions,
        initData() {
            this.getPost();
        },
        getPost() {
            this.params = this.$route.params;

            let url = `api/posts/${this.params.id}`
            this.$http.get(url)
            .then( (response) => {
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
                  this.post = response.data
                }
            })
            .catch(function (error) {
                console.log(error);
            })
            .finally(() => {
                // this.isLoadingPost = false
            })
        },
        submitComment() {
          this.isLoadingComment = true;
          this.errors = null;

          this.$http.post('api/comments', this.formData)
          .then( (response) => {
            if (_.has(response, "data.errors")) {
                this.errors = response.data.errors;
              } else if (_.has(response, "data.exception")) {
                // let alertData = {
                //   showAlert: true,
                //   type: "error",
                //   message:
                //     "Sorry our server has problem at the moment. Please come back later. Thank you.",
                // };
                // mutations.setAlert(alertData);
              } else {
                this.getPost()
              }
          })
          .finally(() => {
            this.isLoadingComment = false;
            this.showDiscussionForm = false
            //
          })
        },
        likeUnlikePost(event, likes, post_id) {
          let formData = { post_id: post_id };
          let like_id = this._.get(this._.find(likes, { user_id: this.user.id }), "id", null);
          let url = this.isLikedByUser(likes) ? `api/likes?id=${like_id}` : "api/likes";
          let http;

          if (this.isLikedByUser(likes) && this.isLikedNotDeleted(likes)) {
            http = this.$http.delete(url); // delete
          } else if (this.isLikedByUser(likes) && !this.isLikedNotDeleted(likes)) {
            http = this.$http.put(url); //update
          } else {
            // create
            http = this.$http.post(url, formData);
          }
          http
            .then((response) => {
              if (_.has(response, "data.exception")) {
                console.log(response);
              } else {
                // successs
                this.getPost();
              }
            })
            .catch(function (error) {
              console.log(error);
            })
            .finally(() => {
              //
            });
        },
        isLikedByUser(likes) {
          return !this._.isEmpty(this._.find(likes, { user_id: this.user.id }));
        },
        isLikedNotDeleted(likes) {
          let userLike = this._.find(likes, { user_id: this.user.id });
          return this._.isEmpty(this._.get(userLike, "deleted_at"));
        },
        setAsFavorite(event, favorites, post_id) {
            let formData = { post_id: post_id };
            let favorite_id = this._.get(this._.find(favorites, { user_id: this.user.id }), "id", null);
            let url = this.isFavoriteByUser(favorites) ? `api/favorites?id=${favorite_id}` : "api/favorites";
            let http;

            if (this.isFavoriteByUser(favorites) && this.isFavoriteNotDeleted(favorites)) {
                http = this.$http.delete(url); // delete
            } else if (this.isFavoriteByUser(favorites) && !this.isFavoriteNotDeleted(favorites)) {
                http = this.$http.put(url); //update
            } else {
                // create
                http = this.$http.post(url, formData);
            }
            http
                .then((response) => {
                if (_.has(response, "data.exception")) {
                    console.log(response);
                } else {
                    // successs
                    this.getPost();
                }
                })
                .catch(function (error) {
                console.log(error);
                })
                .finally(() => {
                //
                });
        },
        isFavoriteByUser(favorites) {
          return !this._.isEmpty(this._.find(favorites, { user_id: this.user.id }));
        },
        isFavoriteNotDeleted(favorites) {
          let userLike = this._.find(favorites, { user_id: this.user.id });
          return this._.isEmpty(this._.get(userLike, "deleted_at"));
        },
        toggleDiscussionForm() {
          this.form.description = ' '
          this.showDiscussionForm = !this.showDiscussionForm
        },
        abbreviateNumber(number){

          // what tier? (determines SI symbol)
          var tier = Math.log10(Math.abs(number)) / 3 | 0;

          // if zero, we don't need a suffix
          if(tier == 0) return number;

          // get suffix and determine scale
          var suffix = this.SI_SYMBOL[tier];
          var scale = Math.pow(10, tier * 3);

          // scale the number
          var scaled = number / scale;

          // format number and add suffix
          return scaled.toFixed(1) + suffix;
        },
    },
    computed: {
        ...getters,
        anyError: function() {
          return this.$v.form.$anyError || _.values(this.form).some((v) => v == null || v == '' );
        },
        formData: function() {
          let formData = this.form;
          formData.user_id = this.post.user_id;
          formData.post_id = this.post.id;

          return formData
        }
    }
};
</script>
<style scoped lang="scss">
@import "../../../sass/imports";

.post {
  margin-top: $base-comp-gap-y;
  margin-right: $base-comp-gap-x;
}

.comment {
  padding: $base-gap;
}

</style>