<template>
  <div class="notif-list">
      <h3 class="header-text">Notifications</h3>
      <div v-show="isToggled" class="tab-more">
        <div class="list-group list-group-light">
          <router-link
            v-for="(noti, key, index) in notifications"
            :key="key"
            :to="getLinkData(noti)"
            :class="`list-group-item list-group-item-action border-0 ripple ${
              $route.name == menu.name ? 'active' : ''
            }`"
            aria-current="true"
          >
            <!-- like -->
            <div v-show="noti.type == NOTI_TYPE['LIKE']">
              <img
                class="w-11 h-11 rounded-full"
                src="../../../assets/icons/user-logo.png"
                alt="Chronostep Community"
              />
              <div
                class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 bg-red-600 rounded-full border border-white dark:border-gray-800"
              >
                <svg
                  class="w-3 h-3 text-white"
                  aria-hidden="true"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    fill-rule="evenodd"
                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
              </div>
            </div>
            <div
              v-show="noti.type == NOTI_TYPE['LIKE']"
              class="pl-3 w-full text-gray-900"
            >
              <div v-html="_.get(noti, 'data.message')"></div>
              <div class="text-xs text-blue-600 dark:text-blue-500">
                {{ moment(noti.created_at).fromNow() }}
              </div>
            </div>
          </router-link>
        </div>
      </div>
      <div class="notif-list">
        <router-link
          v-for="(noti, key, index) in notifications"
          :key="key"
          :to="getLinkData(noti)"
          class="flex py-3 px-6 hover:bg-gray-100 dark:hover:bg-gray-700">
          <!-- like -->
          <div v-show="noti.type == NOTI_TYPE['LIKE']">
            <img
              class="w-11 h-11 rounded-full"
              src="../../../assets/icons/user-logo.png"
              alt="Chronostep Community"
            />
            <div
              class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 bg-red-600 rounded-full border border-white dark:border-gray-800"
            >
              <svg
                class="w-3 h-3 text-white"
                aria-hidden="true"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                  clip-rule="evenodd"
                ></path>
              </svg>
            </div>
          </div>
          <div
            v-show="noti.type == NOTI_TYPE['LIKE']"
            class="pl-3 w-full text-gray-900"
          >
            <div v-html="_.get(noti, 'data.message')"></div>
            <div class="text-xs text-blue-600 dark:text-blue-500">
              {{ moment(noti.created_at).fromNow() }}
            </div>
          </div>
        </router-link>
      </div>
    </div>
  </div>
</template>
<script>

  import { getters, mutations } from "../../store";

  export default ({
    data() {
      return {
        notificationStatus: false,
        notifications: [],
      };
    },
    created() {
      this.NOTI_TYPE = {
        PRESENTATION: "",
        FOLLOW: "",
        LIKE: "App\\\Notifications\\\PostNotification",
        COMMENT: "",
        VIDEO: "",
      };
    },
    mounted() {
      this.init();
      window.Echo.channel("usernotify." + this.user.id)
        .subscribed(() => {
          console.log("Echo connected to usernotify channel!");
        })
        .listen(".UserNotify", (data) => {
          console.log('Broadcast:' + JSON.stringify(data))
          this.notifications.unshift(data);
        });
    },
    computed: {
      ...getters,
    },
    methods: {
      init() {
        this.getNotif();
      },
      getNotif() {
        this.$http
          .get("api/notifications")
          .then((response) => {
            if (_.has(response, "data.exception")) {
              console.log(response);
            } else {
              // successs
              this.notifications = response.data;
            }
          })
          .catch(function (error) {
            console.log(error);
          })
          .finally(() => {
            //
          });
      },
      getLinkData(noti) {
        let link = noti.data.link;
        let slugId = link.split("/")[4];

        return {
          path: '/' + link.split('/')[3] + '/' + slugId,
          params: slugId
        };
      }
    }
  })
</script>
<style lang="scss" scoped>
@import '../../../sass/imports';

@media screen and (max-width: 1200px) {
  .notif-list {
    height: 100%;
    margin: $base-gap
  }

  #dropdownNotificationButton {
    position: fixed;
    bottom: 50px;
    right: 0px;
    height: 50px;
    width: 50px;
    z-index: $z-index-level-3;
    border-radius: 50%;
    color: black;
  }
}
</style>