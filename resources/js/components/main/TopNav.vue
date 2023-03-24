<template>
  <nav
    class="bg-bt-secondary border-gray-200 px-6 sm:px-4 md:px-20 py-2.5 rounded dark:bg-gray-900 relative"
  >
    <div class="flex flex-wrap justify-between md:justify-start gap-6 items-center mx-0">
      <router-link :to="{ name: 'landing-page' }" class="flex items-center">
        <img class="h-12" src="../../../assets/chronostep.png" />
        <!-- <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">Chronostep</span> -->
      </router-link>
      <div class="flex md:order-1">
        <button
          type="button"
          data-collapse-toggle="navbar-search"
          aria-controls="navbar-search"
          aria-expanded="false"
          class="md:hidden text-b-link rounded-lg text-sm p-2.5 mr-1"
        >
          <svg
            class="w-5 h-5"
            aria-hidden="true"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
              clip-rule="evenodd"
            ></path>
          </svg>
          <span class="sr-only">Search</span>
        </button>
        <div class="hidden relative md:block">
          <div
            class="flex absolute inset-y-0 right-2 items-center pl-3 pointer-events-none"
          >
            <svg
              class="w-5 h-5 text-b-link"
              aria-hidden="true"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                clip-rule="evenodd"
              ></path>
            </svg>
            <span class="sr-only">Search icon</span>
          </div>
          <input
            type="text"
            id="search-navbar"
            class="w-[250px] block p-2 pl-5 rounded-lg border border-gray-300 sm:text-sm focus:ring-blue-500 focus:border-blue-500"
            placeholder="Search..."
          />
        </div>
        <button
          data-collapse-toggle="navbar-search"
          type="button"
          class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
          aria-controls="navbar-search"
          aria-expanded="false"
        >
          <span class="sr-only">Open menu</span>
          <svg
            class="w-6 h-6"
            aria-hidden="true"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
              clip-rule="evenodd"
            ></path>
          </svg>
        </button>
      </div>
      <div
        class="hidden justify-between items-center w-full md:flex md:w-auto md:order-1"
        id="navbar-search"
      >
        <div class="relative mt-3 md:hidden">
          <div
            class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none"
          >
            <svg
              class="w-5 h-5 text-gray-500"
              aria-hidden="true"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                clip-rule="evenodd"
              ></path>
            </svg>
          </div>
          <input
            type="text"
            id="search-navbar"
            class="block p-2 pl-10 w-full bg-gray-50 rounded-lg border border-gray-300 sm:text-sm"
            placeholder="Search..."
          />
        </div>
        <ul
          class="flex flex-col mt-4 sm:mt-0 bg-bt-secondary rounded-lg md:flex-row md:space-x-4 md:mt-0:md:text-sm md:font-medium md:border-0 md:bg-bt-secondary static sm:absolute right-4 sm:right-20"
        >
          <li
            v-show="!isLoggedIn"
            class="flex sm:items-center justify-center text-center"
          >
            <router-link
              :to="{ name: 'login' }"
              class="block w-full text-b-link rounded hover:bg-b-primary sm:hover:bg-transparent p-2"
              aria-current="page"
              >Login</router-link
            >
          </li>
          <li
            v-show="!isLoggedIn"
            class="flex sm:items-center justify-center text-center"
          >
            <router-link
              :to="{ name: 'register' }"
              class="block w-full sm:border-2 sm:border-b-link p-2 text-b-link hover:bg-b-primary sm:hover:bg-transparent rounded-lg"
              >Create Account</router-link
            >
          </li>
          <li
            v-show="isLoggedIn"
            class="flex justify-center sm:justify-start bg-b-link sm:bg-transparent rounded"
          >
            <!-- Dropdown menu -->
            <notification></notification>
          </li>
          <li v-show="isLoggedIn" class="flex justify-center sm:justify-start">
            <button
              id="user-menu-button"
              aria-expanded="false"
              data-dropdown-toggle="user-dropdown"
              data-dropdown-placement="bottom"
              class="flex w-full items-center p-2 text-b-link hover:bg-b-primary sm:hover:bg-transparent rounded md:order-2"
            >
              <img
                class="w-11 border-2 border-b-info h-11 rounded-full hidden sm:block"
                src="../../../assets/icons/user-logo.png"
                alt="Jese image"
              />
              <span class="ellipsis w-[100px] mx-auto sm:mx-0 sm:ml-4 text-b-info capitalize">{{
                _.get(user, "name")
              }}</span>
            </button>

            <!-- Dropdown menu -->
            <div
              class="hidden w-[250px] z-50 my-4 text-base list-none bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600"
              id="user-dropdown"
            >
              <div class="py-3 px-6">
                <span class="block ellipsis block text-sm font-medium text-gray-500 truncate dark:text-gray-400 text-sm text-gray-900 dark:text-white capitalize">{{
                  _.get(user, "name")
                }}</span>
                <span class="block ellipsis text-sm font-medium text-gray-500 truncate dark:text-gray-400"
                  >{{ _.get(user, "email") }}</span
                >
              </div>
              <ul class="py-1" aria-labelledby="user-menu-button">
                <li>
                  <router-link
                    :to="{ name: 'dashboard' }"
                    class="block py-2 px-6 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                    >Dashboard</router-link
                  >
                </li>
                <li>
                  <span
                    @click="signOut()"
                    class="block py-2 px-6 text-sm text-gray-700 hover:bg-gray hover:cursor-pointer dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white router-link-exact-active router-link-active"
                    >Sign Out</span
                  >
                </li>
              </ul>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { getters, mutations } from "../../store";
import { required, minLength, sameAs, email, helpers } from "vuelidate/lib/validators";

import Notification from '../Notification.vue'

export default {
  name: "top-nav",
  props: ["data"],
  components: { Notification },
  data() {
    return {
      //
    };
  },
  methods: {
    ...mutations,
    signOut() {
      localStorage["chronoknowledge.jwt"] = null;
      localStorage["chronoknowledge.user"] = null;
      mutations.setUser(null);
      mutations.setIsLoggedIn(false);
      document.getElementById("user-dropdown").classList.add("hidden");
      this.$router.push("/");
    }
  },
  computed: {
    ...getters,
  },
};
</script>

<style scoped lang="scss">
@import "../../../sass/imports";

.top-nav {
  position: absolute;
  top: 0px;
  width: 100%;
  height: 5rem;
  padding: 0.5rem 2rem;
  z-index: $z-index-nav-drawer;
  background-color: $brand-theme-color-secondary;

  .nav-content {
    height: 100%;
    display: flex;
    grid-gap: $base-gap;

    .nav-menu {
      display: flex;
      grid-gap: $base-gap;

      .ck-logo {
        height: 3.5rem;
        margin: auto 0;
      }
      .ck-search {
        display: grid;
        align-items: center;
      }
    }

    .nav-links {
      flex-grow: 1;
      display: flex;
      flex-flow: row-reverse;

      & > div {
        display: flex;
        align-items: center;
        grid-gap: $base-gap;
      }

      .profile {
        display: flex;
        align-items: center;
        grid-gap: $base-gap;
      }

      .notif-bell {
        background-color: rgba(244, 67, 54);
        width: 12px;
        height: 12px;
        border-radius: 50%;
        font-size: 10px;
        line-height: 1.3;
        top: 4px;
        right: -2px;
      }
    }
  }

  .nav-content > div {
    height: 100%;
  }
}
</style>
