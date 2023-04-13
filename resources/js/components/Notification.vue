<template>
  <div id="notification-component">
    <button
    @click="showNotificationList"
    id="dropdownNotificationButton"
    data-dropdown-toggle="dropdownNotification"
    :class="[
      'relative d-flex justify-items-center align-items-center sm:bg-transparent ripple',
      { 'bg-transparent': isActiveNotifList }
    ]"
    type="button"
  >
    <span
      v-show="notifications.length"
      class="absolute ml-2 mt-1 bg-b-danger b-danger text-white text-2xs leading-3 rounded-full hover:cursor-pointer"
      >{{ notifications.length }}</span
    >
    <i class="fa-regular fa-bell m-auto text-lg text-b-info"></i>
  </button>
  <div
      id="dropdownNotification"
      class="hidden w-[400px] z-20 max-w-sm bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-800 dark:divide-gray-700"
      aria-labelledby="dropdownNotificationButton"
      data-popper-reference-hidden=""
      data-popper-escaped=""
      data-popper-placement="bottom"
    >
      <div
        class="block py-2 px-6 font-medium text-center text-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white"
      >Notifications
      </div>
      <div class="divide-y divide-gray-100 dark:divide-gray-700">
        <router-link
          v-for="(noti, key, index) in notifications"
          :key="key"
          :to="getLinkData(noti)"
          class="flex py-3 px-6 hover:bg-gray-100 dark:hover:bg-gray-700"
        >
          <!-- presentation -->
          <!-- <div v-show="noti.type == NOTI_TYPE['PRESENTATION']" class="flex-shrink-0">
            <img
              class="w-11 h-11 rounded-full"
              src="/docs/images/people/profile-picture-1.jpg"
              alt="Jese image"
            />
            <div class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 bg-blue-600 rounded-full border border-white dark:border-gray-800">
              <svg
                class="w-3 h-3 text-white"
                aria-hidden="true"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"
                ></path>
                <path
                  d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"
                ></path>
              </svg>
            </div>
          </div>
          <div v-show="noti.type == NOTI_TYPE['PRESENTATION']" class="pl-3 w-full">
              <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400">New message from <span class="font-semibold text-gray-900 dark:text-white">{{ _.get(noti, 'name') }}</span>: "{{ _.get(noti, 'message') }}"</div>
              <div class="text-xs text-blue-600 dark:text-blue-500">{{ moment(noti.date).fromNow() }}</div>
          </div> -->

          <!-- following -->
          <!-- <div v-show="noti.type == NOTI_TYPE['FOLLOW']" class="flex-shrink-0">
            <img
              class="w-11 h-11 rounded-full"
              src="/docs/images/people/profile-picture-2.jpg"
              alt="Joseph image"
            />v
            <div class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 bg-gray-900 rounded-full border border-white dark:border-gray-800">
              <svg
                class="w-3 h-3 text-white"
                aria-hidden="true"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"
                ></path>
              </svg>
            </div>
          </div>
          <div v-show="noti.type == NOTI_TYPE['FOLLOW']" class="pl-3 w-full">
              <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400"><span class="font-semibold text-gray-900 dark:text-white">{{ _.get(noti, 'name') }}</span> and <span class="font-medium text-gray-900 dark:text-white">{{ _.get(noti, 'followsCount') }} others</span> started following you.</div>
              <div class="text-xs text-blue-600 dark:text-blue-500">{{ moment(noti.date).fromNow() }}</div>
          </div> -->

          <!-- like -->
          <div v-show="noti.type == NOTI_TYPE['LIKE']">
            <img
              class="w-11 h-11 rounded-full"
              src="../../assets/icons/user-logo.png"
              alt="Bonnie image"
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

          <!-- comment -->
          <!-- <div v-show="noti.type == NOTI_TYPE['COMMENT']" class="flex-shrink-0">
            <img
                class="w-11 h-11 rounded-full"
                src="/docs/images/people/profile-picture-4.jpg"
                alt="Leslie image"
              />
            <div class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 bg-green-400 rounded-full border border-white dark:border-gray-800">
              <svg
                class="w-3 h-3 text-white"
                aria-hidden="true"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  fill-rule="evenodd"
                  d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z"
                  clip-rule="evenodd"
                ></path>
              </svg>
            </div>
          </div>
          <div v-show="noti.type == NOTI_TYPE['COMMENT']" class="pl-3 w-full">
              <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400"><span class="font-semibold text-gray-900 dark:text-white">{{ _.get(noti, 'name') }}</span> mentioned you in a comment: <span class="font-medium text-blue-500" href="#">"{{ _.get(noti, 'message') }}"</span> what do you say?</div>
              <div class="text-xs text-blue-600 dark:text-blue-500">{{ moment(noti.date).fromNow() }}</div>
          </div> -->

          <!-- video -->
          <!-- <div v-show="noti.type == NOTI_TYPE['VIDEO']" class="flex-shrink-0">
            <img
              class="w-11 h-11 rounded-full"
              src="/docs/images/people/profile-picture-5.jpg"
              alt="Robert image"
            />
            <div class="flex absolute justify-center items-center ml-6 -mt-5 w-5 h-5 bg-purple-500 rounded-full border border-white dark:border-gray-800">
              <svg
                class="w-3 h-3 text-white"
                aria-hidden="true"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"
                ></path>
              </svg>
            </div>
          </div>
          <div v-show="noti.type == NOTI_TYPE['VIDEO']" class="pl-3 w-full">
              <div class="text-gray-500 text-sm mb-1.5 dark:text-gray-400"><span class="font-semibold text-gray-900 dark:text-white">{{ _.get(noti, 'name') }}</span> posted a new video: {{ _.get(noti, 'message') }}</div>
              <div class="text-xs text-blue-600 dark:text-blue-500">{{ moment(noti.date).fromNow() }}</div>
          </div> -->
        </router-link>
      </div>
      <a
        href="#"
        class="block py-2 text-sm font-medium text-center text-gray-900 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-white"
      >
        <div class="inline-flex items-center">
          <svg
            class="mr-2 w-4 h-6 text-gray-500 dark:text-gray-400"
            aria-hidden="true"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
            <path
              fill-rule="evenodd"
              d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
              clip-rule="evenodd"
            ></path>
          </svg>
          View all
        </div>
      </a>
    </div>
  </div>
</template>
<script>

  import { getters, mutations } from "../store";

  export default ({
    name: 'notification',
    data() {
      return {
        isActiveNotifList: false,
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
      },
      showNotificationList() {
        console.log('showNotificationList');
        this.$router.push({ name: 'notif-list' });
      }
    }
  })
</script>
<style lang="scss">
@import '../../sass/imports';

@media screen and (max-width: 1200px) {

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