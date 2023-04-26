<template>
  <div>
    <top-nav></top-nav>
    <main>
      <router-view></router-view>
    </main>
    <CommunityFooter></CommunityFooter>
    <TabBar v-show="isMobile"></TabBar>
    <!-- Modals -->

    <!-- Pop ups -->
    <ui-alert
      :showAlert="alert.showAlert"
      :type="alert.type"
      :removeIcon="alert.removeIcon"
      :disableAnimation="alert.disableAnimation"
      :dismissible="alert.dismissible"
      :timer="alert.timer"
    >
      {{ alert.message }}
    </ui-alert>

    <notification v-show="isMobile"></notification>
    <loading v-show="loading" :type="loader.type.isByPage" :withLoading="true"></loading>
  </div>
</template>

<script>
import Vue from "vue";
import { getters, mutations, actions } from "../store";
import TopNav from "../components/main/TopNav.vue";
import CommunityFooter from "../components/main/Footer.vue";
import TabBar from "../components/main/TabBar.vue";
import ConfirmationModal from "../components/Confirmation.vue";
import Multiselect from "vue-multiselect";
import Notification from '../components/Notification.vue'

Vue.component("UiButton", require("../components/UiButton.vue").default);
Vue.component("UiAlert", require("../components/UiAlert.vue").default);
Vue.component("ErrorInput", require("../components/ErrorInput.vue").default);
Vue.component("Loading", require("../components/Loading.vue").default);
Vue.component("ClipLoader", require("vue-spinner/src/ClipLoader.vue").default);
Vue.component("multiselect", Multiselect);

export default {
  data() {
    return {
      param_reset_token: "",
    };
  },
  components: { TopNav, ConfirmationModal, CommunityFooter, TabBar, Notification },
  beforeMount() {
    this.initApp();
  },
  computed: {
    ...getters,
  },
  methods: {
    ...mutations,
    ...actions,
    initApp() {
      mutations.setLoading(true);
      this.checkPasswordReset();
      let user = localStorage["chronoknowledge.user"]
        ? JSON.parse(localStorage["chronoknowledge.user"])
        : null;

      if (user) {
        mutations.setUser(user);
      }
    },
    checkPasswordReset() {
      let parameterName = "reset_token";
      let tmp = [];

      location.search
        .substr(1)
        .split("&")
        .forEach((item) => {
          tmp = item.split("=");
          if (tmp[0] == parameterName) {
            let reset_password = localStorage["chronoknowledge.reset_password"]
              ? JSON.parse(localStorage["chronoknowledge.reset_password"])
              : null;

            if (
              reset_password &&
              reset_password.reset_token == decodeURIComponent(tmp[1])
            ) {
              this.$router.push({
                name: "reset-password",
                params: { type: "reset-password" },
              });
            }
          }
        });
    },
  },
};
</script>
<style scoped lang="scss">
main {
  min-height: 100vh;
}
</style>
