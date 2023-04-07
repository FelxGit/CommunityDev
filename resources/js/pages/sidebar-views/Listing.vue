<template>
  <div class="listing">
    <div class="max-h-[83.5vh] overflow-y-scroll tab-pane fade show active grid gap-6 relative"
        >
      <div
        v-for="(post, index) in posts"
        :key="index"
        class="grid p-6 gap-6 border border-white bg-bt-secondary rounded-lg"
      >
        <div class="flex gap-6">
          <div>
            <img
              src="../../../assets/icons/user-logo.png"
              class="h-12 border border-white rounded-full"
            />
          </div>
          <div class="grid">
            <span class="text-b-info font-bold">{{
              _.get(post, "user.name", "")
            }}</span>
            <span class="text-b-mute">{{ _.get(post, "user.job", "") }}</span>
            <span class="text-gray-700"
              ><small>{{
                moment(_.get(post, "created_at", "")).format("MMM Do YYYY, h:mm A")
              }}</small></span
            >
          </div>
        </div>
        <div>
          <h3 class="font-bold">{{ _.get(post, "title", "") }}</h3>
        </div>
        <div class="text-b-dark">
          <span v-for="(tag, index) in post.tags" :key="index"
            >{{ _.get(tag, "title", "") }}
          </span>
        </div>
        <div class="flex gap-6">
          <span>
            <span>{{ abbreviateNumber(_.get(post, 'likes', []).length) }}</span> 
            <span
                @click="likeUnlikePost($event, post.likes, post.id)"
                :class="[
                {
                    'text-b-link': user && isLikedByUser(post.likes) && isLikeNotDeleted(post.likes),
                },
                ]"
                role="button"
            >
                <i class="fa-regular fa-thumbs-up pr-3"></i>{{ lang.get("words.Likes") }}
            </span>
          </span>
          <span @click="redirectToPost(post)" role="button"
            ><i class="fa-regular fa-comment pr-3"></i
            ><span>{{ lang.get("words.Comments") }}</span></span
          >
          <span>
            <span>{{ abbreviateNumber(_.get(post, 'favorites', []).length) }}</span> 
            <span
            @click="setAsFavorite($event, post.favorites, post.id)" role="button"
            :class="[
                {
                    'text-amber-400': user && isFavoriteByUser(post.favorites) && isFavoriteNotDeleted(post.favorites),
                },
            ]"
            ><i class="fa-regular fa-star pr-3" role="button"></i
            ><span>{{ lang.get("words.Favorites") }}</span></span>
          </span>
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
            posts: null,
            lazyLoadData: null,
            posts: [],
            isLoadingPost: false,
            page: 1,
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
  mounted() {
    this.getNextPost();
  },
  methods: {
    ...mutations,...actions,
    initData() {
        this.getPosts();
    },
    getPosts() {
        this.isLoadingPost = true;
        this.params = this.$route.params;

        if (this.lazyLoadData != []) {
          this.page++;
        }

        let url = `api/posts?page=${this.page}`
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
              if (!this.isLazyLoaded) {
                  this.resetPosts();
              }

              this.posts.push(...response.data.data)
              this.lazyLoadData = response.data
            }
        })
        .catch(function (error) {
            console.log(error);
        })
        .finally(() => {
            this.isLoadingPost = false
            this.isLazyLoaded = false
        })
    },
    getNextPost() {

      setTimeout(() => {
        let tabscontent = $(".listing div.tab-pane");
        tabscontent.scroll(() => {
            let bottomOfWindow = tabscontent.scrollTop() + window.innerHeight  > tabscontent.prop('scrollHeight');
            if (bottomOfWindow && !this.isLoadingPost) {
                this.isLazyLoaded = true;
                this.getPosts()
            }
        });
      }, 10000);
    },
    likeUnlikePost(event, likes, post_id) {
      let formData = { post_id: post_id };
      let like_id = this._.get(this._.find(likes, { user_id: this.user.id }), "id", null);
      let url = this.isLikedByUser(likes) ? `api/likes?id=${like_id}` : "api/likes";
      let http;

      if (this.isLikedByUser(likes) && this.isLikeNotDeleted(likes)) {
        http = this.$http.delete(url); // delete
      } else if (this.isLikedByUser(likes) && !this.isLikeNotDeleted(likes)) {
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
            this.getPosts();
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
    isLikeNotDeleted(likes) {
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
              this.getPosts();
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
    redirectToPost(post) {
      let param = post;
      this.$router.push({
          path: "post/" + post.id,
          params: { id: post.id }
      });
    },
    resetPosts() {
      this.posts = [];
      this.topPosts = [];
      this.latestPosts = [];
      this.lazyLoadData = [];
      this.page = 1;
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
    }
  },
  computed: {
      ...getters,
      anyError: function() {
        return this.$v.form.$anyError || _.values(this.form).some((v) => v == null || v == '' );
      },
      formData: function() {
        let formData = this.form;
        formData.user_id = this.favorite.user_id;
        formData.favorite_id = this.favorite.id;

        return formData
      }
  }
};
</script>
<style scoped lang="scss">
@import "../../../sass/imports";

.listing {
  margin-top: $base-comp-gap-y;
  margin-right: $base-comp-gap-x;
}

.comment {
  padding: $base-gap;
}

</style>