<template>
  <div class="homeComponent" :style="[isLoggedIn ? landingLoggedInStyle : '']">
    <div v-show="!isLoggedIn" class="about">
      <h3 class="font-bold leadinng-8">
        <span class="text-b-info">{{ lang.get("words.Chronostep") }}</span>
        <span class="text-b-create">{{ lang.get("words.Community") }}</span>
      </h3>
      <div>
        <p class="font-bold">{{ lang.get("words.AboutHeader1") }}</p>
        <p class="mt-6">{{ lang.get("words.AboutHeader2") }}</p>
      </div>
      <div class="grid font-bold">
        <button class="px-4 py-2 border-2 border-b-link text-b-link rounded-lg">
          <router-link :to="{ name: 'register' }">{{
            lang.get("words.CreateAccount")
          }}</router-link>
        </button>
        <button class="px-4 py-2 text-b-mute">
          <router-link :to="{ name: 'login' }">{{ lang.get("words.Login") }}</router-link>
        </button>
      </div>
    </div>
    <div class="posts">
      <div class="flex justify-between items-center">
        <ul
          class="nav nav-tabs flex gap-6 list-none border-b-0 pl-0 font-bold"
          id="tabs-tab"
          role="tablist"
        >
          <li class="nav-item" role="presentati on">
            <a
              @click="setTab(POST_DISPLAY_RELEVANT)"
              href="#relevant"
              :class="[
                { 'text-b-mute': post_display_type == POST_DISPLAY_RELEVANT },
                { 'text-b-primary': post_display_type != POST_DISPLAY_RELEVANT },
              ]"
              id="relevant-tab"
              data-bs-toggle="pill"
              data-bs-target="#relevant"
              role="tab"
              aria-controls="relevant"
              aria-selected="true"
              >{{ lang.get("words.Relevant") }}</a
            >
          </li>
          <li class="nav-item" role="presentation">
            <a
              @click="setTab(POST_DISPLAY_LATEST)"
              href="#latest"
              :class="[
                { 'text-b-mute': post_display_type == POST_DISPLAY_LATEST },
                { 'text-b-primary': post_display_type != POST_DISPLAY_LATEST },
              ]"
              id="latest-tab"
              data-bs-toggle="pill"
              data-bs-target="#latest"
              role="tab"
              aria-controls="latest"
              aria-selected="false"
              >{{ lang.get("words.Latest") }}</a
            >
          </li>
          <li class="nav-item" role="presentation">
            <a
              @click="setTab(POST_DISPLAY_TOP)"
              href="#top"
              :class="[
                { 'text-b-mute': post_display_type == POST_DISPLAY_TOP },
                { 'text-b-primary': post_display_type != POST_DISPLAY_TOP },
              ]"
              id="top-tab"
              data-bs-toggle="pill"
              data-bs-target="#top"
              role="tab"
              aria-controls="top"
              aria-selected="false"
              >{{ lang.get("words.Top") }}</a
            >
          </li>
        </ul>
        <div>
          <button
            @click="showPostCreate = !showPostCreate"
            v-show="isLoggedIn"
            class="block w-full sm:border-2 sm:border-b-link p-2 text-b-link hover:bg-b-primary sm:hover:bg-transparent rounded-lg"
          >
            <i v-if="!showPostCreate" class="fa-solid fa-plus"></i>
            <i v-else class="fa fa-minus" aria-hidden="true"></i>
            Add Post
          </button>
        </div>
      </div>
      <div class="post-create">
        <PostForm
          v-show="showPostCreate"
          :categories="categories"
          @initData="initData()"
          @hidePostCreate="showPostCreate = !showPostCreate"
        ></PostForm>
      </div>

      <div class="tab-content" id="tabs-tabContent">
        <div
          v-show="post_display_type == POST_DISPLAY_RELEVANT"
          class="max-h-[83.5vh] overflow-y-scroll tab-pane fade show active grid gap-6 relative"
          id="relevant"
          role="tabpanel"
          aria-labelledby="relevant-tab"
        >
          <!-- <div
            v-show="isLoadingPost"
            class="absolute w-full h-full bg-form-overlay"
          ></div> -->
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
                    <span>{{ abbreviateNumber(_.filter(_.get(post, 'likes', []), v => v.deleted_at == null).length) }}</span>
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
        <div
          v-show="post_display_type == POST_DISPLAY_LATEST"
          class="max-h-[83.5vh] overflow-y-scroll tab-pane fade show active grid gap-6 relative"
          id="latest"
          role="tabpanel"
          aria-labelledby="latest-tab"
        >
        <!-- <div
            v-show="isLoadingPost"
            class="absolute w-full h-full bg-form-overlay"
          ></div> -->
          <div
            v-for="(post, index) in latestPosts"
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
              <span @click="redirectToPost(post)" role="button"
                ><i class="fa-regular fa-comment pr-3"></i
                ><span>{{ lang.get("words.Comments") }}</span></span
              >
              <span
                ><i class="fa-regular fa-star pr-3" role="button"></i
                ><span>{{ lang.get("words.Favorites") }}</span></span
              >
            </div>
          </div>
        </div>
        <div
          v-show="post_display_type == POST_DISPLAY_TOP"
          class="max-h-[83.5vh] overflow-y-scroll tab-pane fade show active grid gap-6 relative"
          id="top"
          role="tabpanel"
          aria-labelledby="latest-tab"
        >
        <div
            v-for="(post, index) in topPosts"
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
              <span @click="redirectToPost(post)" role="button"
                ><i class="fa-regular fa-comment pr-3"></i
                ><span>{{ lang.get("words.Comments") }}</span></span
              >
              <span
                ><i class="fa-regular fa-star pr-3" role="button"></i
                ><span>{{ lang.get("words.Favorites") }}</span></span
              >
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="category">
      <div class="grid grid-cols-1 gap-6">
        <h4 class="font-bold text-b-mute">{{ lang.get("words.Category") }}</h4>
        <ul class="category-list ml-2">
          <li v-for="(category, index) in categories" :key="index">
            <a
              href="#category"
              @click="selectedCategory = category.title"
              :class="[
                { 'text-b-link': true },
                { underline: selectedCategory == category.title },
              ]"
            >
              {{ category.title }}
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div v-show="!isLoggedIn" class="app">
      <div class="bg-white p-6 rounded-lg text-black">
        <div>
          <h3 class="font-bold">{{ lang.get("words.MobileApplication") }}</h3>
          <h3 class="font-bold">{{ lang.get("words.LandingPage") }}</h3>
        </div>
        <div class="mt-6">
          <p class="text-small">{{ lang.get("words.MobileAdDescription") }}</p>
        </div>
        <div class="mt-6 flex gap-2">
          <button class="flex px-2 py-1 gap-2 items-center bg-black text-white">
            <span><i class="fa-brands fa-apple text-[2rem]"></i></span>
            <span class="text-[10px]">
              <span
                >{{ lang.get("words.DownloadOnThe") }}<br />
                <span class="font-bold"> App Store</span>
              </span>
            </span>
          </button>
          <button class="flex px-2 py-1 gap-2 items-center bg-black text-white">
            <span><i class="fa-brands fa-google-play text-[2rem]"></i></span>
            <span class="text-[10px]">
              <span
                >{{ lang.get("words.GetItOn") }}<br />
                <span class="font-bold"> Google Store</span>
              </span>
            </span>
          </button>
        </div>
      </div>
    </div>
    <div class="popular">
      <div class="grid grid-cols-1 gap-6">
        <h4 class="font-bold text-b-mute">{{ lang.get("words.PopularTags") }}</h4>
        <ul class="popular-list ml-2">
          <li v-for="(tag, index) in tags" :key="index">
            <span class="underline">
              {{ _.get(tag, "title") }}
            </span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import { getters, mutations, actions } from "../../store";
import PostForm from "../posts/PostForm.vue";

export default {
  components: { PostForm },
  data() {
    return {
      showPostCreate: false,
      lazyLoadData: null,
      posts: [],
      topPosts: [],
      latestPosts: [],
      categories: [],
      tags: [],
      selectedCategory: "",
      post_display_type: null,
      isLoadingCate: false,
      isLoadingTag: false,
      isLoadingPost: false,
      page: 1,
    };
  },
  created() {
    this.POST_DISPLAY_RELEVANT = "RELEVANT";
    this.POST_DISPLAY_LATEST = "LATEST";
    this.POST_DISPLAY_TOP = "TOP";

    this.SI_SYMBOL = ["", "k", "M", "G", "T", "P", "E"];

    this.post_display_type = this.POST_DISPLAY_RELEVANT;
    this.initData();
  },
  mounted() {
    this.getNextPost();
  },
  methods: {
    ...mutations,
    ...actions,
    initData() {
      this.getCategories();
      this.getTags();
      this.getPosts();
    },
    setTab(val) {
      this.post_display_type = val;

      if (
        (val == this.POST_DISPLAY_RELEVANT) ||
        (val == this.POST_DISPLAY_LATEST) ||
        (val == this.POST_DISPLAY_TOP)
      ) {
        this.page = 1;
        this.getPosts();
      }
    },
    signOut() {
      localStorage["chronoknowledge.jwt"] = null;
      localStorage["chronoknowledge.user"] = null;
      mutations.setUser(null);
      mutations.setIsLoggedIn(false);
    },
    getCategories() {
      this.isLoadingCate = true;

      if (this._.isEmpty(this.categories))
        this.$http
          .get("api/categories")
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
              this.categories = response.data;
              // this.selectedCategory = this.categories[0] ? this.categories[0].title : ''
              // console.log(this.selectedCategory);
            }
          })
          .catch(function (error) {
            console.log(error);
          })
          .finally(() => {
            this.isLoadingCate = false;
          });
    },
    getTags() {
      this.isLoadingTags = true;

      this.$http
        .get("api/tags?acquireByPopularity=true")
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
    getPosts() {
      this.isLoadingPost = true
      let page;
      if (this.lazyLoadData != []) {
        page = this._.isEmpty(this.lazyLoadData)? this.page : this.page++;
      }

      let url = `api/posts?page=${page}&categoryTitle=${this.selectedCategory}&post_display_type=${this.post_display_type}`;
      this.$http
        .get(url)
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
                if (!this.isLazyLoaded) {
                    this.resetPosts();
                }

                if(this.post_display_type == this.POST_DISPLAY_TOP) {
                    this.topPosts.push(...response.data.data)
                } else if(this.post_display_type == this.POST_DISPLAY_LATEST) {
                    this.latestPosts.push(...response.data.data)
                } else {
                    this.posts.push(...response.data.data)
                }
                this.lazyLoadData = response.data;
            }
        })
        .catch(function (error) {
          console.log(error);
        })
        .finally(() => {
          this.isLoadingPost = false;
          this.isLazyLoaded = false
        });
    },
    getNextPost() {

      setTimeout(() => {
        let tabscontent = $(".tab-content > div");
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
        this.$router.push({ name: 'post', params: { id: post.id } });
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
    landingLoggedInStyle() {
      return {
        "grid-template-areas": `'posts posts category''posts posts popular'`,
        "background-color": "none",
        padding: "0",
      };
    }
  },
  watch: {
    selectedCategory: {
      handler(value) {
        this.getPosts();
      },
    },
  },
};
</script>
<style scoped lang="scss">
@import "../../../sass/imports";

.homeComponent {
  display: grid;
  grid-template-areas:
    "about posts posts category"
    "app posts posts popular";
  grid-template-rows: 1fr 1fr;
  grid-template-columns: 1fr 2fr 1fr;
  grid-gap: $base-comp-gap-y;
  margin: $base-comp-gap-y $base-comp-gap-x 0rem $base-comp-gap-y;
  // height: 100vh;

  .sidebar-menu {
    grid-area: sidebar;
    display: grid;
    grid-row-gap: $base-gap;
    // padding: $base-gap;
    // background-color: $brand-theme-color-secondary;
    border-radius: $ui-default-border-radius;
    width: 300px;
  }

  .about {
    grid-area: about;
    display: grid;
    grid-row-gap: $base-gap;
    padding: $base-gap;
    background-color: $brand-theme-color-secondary;
    border-radius: $ui-default-border-radius;
    // max-width: 400px;
    max-height: 50vh;
    width: 300px;
  }

  .app {
    grid-area: app;
    display: grid;
    grid-row-gap: $base-gap;
    background-color: $brand-theme-color-secondary;
    border-radius: $ui-default-border-radius;
    // max-width: 400px;
    max-height: 40vh;
    width: 300px;
  }

  .posts {
    grid-area: posts;
    display: flex;
    flex-direction: column;
    grid-gap: $base-gap;

    #relevant::-webkit-scrollbar {
      display: none;
    }

    #relevant:hover::-webkit-scrollbar {
      display: block;
    }
  }
  .category {
    grid-area: category;
    max-height: 50vh;
    width: 300px;

    .category-list {
      overflow-y: scroll;
      max-height: calc(40vh - 50px);
    }
  }
  .popular {
    grid-area: popular;
    max-height: 50vh;
    width: 300px;

    .popular-list {
      overflow-y: scroll;
      max-height: calc(40vh - 50px);
    }
  }
}
</style>
