<template>
  <div class="tab-bar">
    <div v-show="isToggled" class="tab-more">
      <div class="list-group list-group-light">
        <router-link
          v-for="(menu, index) in sidebar"
          :key="index"
          :to="{ name: menu.name }"
          :class="`list-group-item list-group-item-action border-0 ripple ${
            $route.name == menu.name ? 'active' : ''
          }`"
          aria-current="true"
        >
          <i :class="`${menu.icon}`" aria-hidden="true"></i>
          <span class="ml-3">{{
            lang.get(`words.${_.startCase(_.get(menu, "name")).replaceAll(" ", "")}`)
          }}</span>
        </router-link>
      </div>
    </div>
    <div class="bottombar">
      <router-link :to="{ name: 'post-create' }" class="ripple relative">
        <label class="round bg-b-info tab-bar-post" for="t0" style="margin-top: -25px">
          <i class="material-icons"><i class="fa fa-plus" aria-hidden="true"></i></i>
        </label>
        <div class="post-back"></div>
      </router-link>
      <router-link
        @click.native="setActiveTab(1)"
        :to="{ name: 'home' }"
        :class="`ripple ${activeTab == HOME ? 'active' : ''}`"
      >
        <label class="round" for="t1"
          ><i class="material-icons"><i class="fa fa-home" aria-hidden="true"></i></i
        ></label>
      </router-link>
      <router-link
        @click.native="setActiveTab(2)"
        :to="{ name: 'user' }"
        :class="`ripple ${activeTab == USER ? 'active' : ''}`"
      >
        <label class="round" for="t2"
          ><i class="material-icons"><i class="fa fa-user" aria-hidden="true"></i></i
        ></label>
      </router-link>
      <a
        @click="
          setActiveTab(3);
          showList();
        "
        role="button"
        :class="`ripple ${activeTab == MORE ? 'active' : ''}`"
      >
        <label class="round" for="t3"><i class="material-icons">more</i></label>
      </a>
    </div>
  </div>
</template>
<script>
import { getters, mutations, actions } from "../../store";
import sidebar from "../../pages/sidebar-views";

export default {
  data() {
    return {
      isToggled: false,
      activeTab: 0,
      sidebar: sidebar,
    };
  },
  mounted() {
    this.HOME = 1;
    this.USER = 2;
    this.MORE = 3;
    console.log(this.$route.name);
  },
  methods: {
    showList(e) {
      console.log("showList");
      console.log(this.isToggled);

      if (this.isToggled) {
        this.isToggled = false;
      } else {
        this.isToggled = true;
      }
    },
    setActiveTab(tabnumber) {
      this.activeTab = tabnumber;
    },
  },
  computed: {
    ...getters,
  },
};
</script>
<style scoped lang="scss">
@import url("https://fonts.googleapis.com/css?family=Montserrat:400");
@import "../../../sass/imports";

.active {
  color: $brand-info;
  margin-top: -10px;
  transition: margin 0.5s;
}
.tab-bar {
  position: fixed;
  bottom: 0;
  width: 100%;
  z-index: $z-index-level-2;

  .tab-more {
    height: calc(100vh - 50px);
    width: 100%;
    padding: $base-comp-gap-x $base-gap $base-gap $base-gap;
    background-color: $brand-theme-color-primary;
    color: white;
    background-color: $brand-theme-color-primary;
  }

  .bottombar {
    background-color: $brand-theme-color-secondary;
    height: 50px;
    width: 100%;
    display: grid;
    justify-content: space-between;
    grid-template-columns: repeat(4, 1fr);
    padding-left: 10px;
    padding-right: 10px;
    text-align: center;
  }
  .bottombar .post-back {
    height: 55px;
    width: 55px;
    position: absolute;
    border-radius: 50%;
    left: 50%;
    transform: translate(-50%, 0);
    background-color: $brand-theme-color-primary;
    margin-top: -45px;
    margin-left: -1px;
  }

  .bottombar input {
    display: none;
  }
  .bottombar .round {
    position: relative;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    -webkit-transform: translate(0, 5px);
    transform: translate(0, 5px);
    -webkit-transition: -webkit-transform 0.3s ease;
    transition: -webkit-transform 0.3s ease;
    transition: transform 0.3s ease;
    transition: transform 0.3s ease, -webkit-transform 0.3s ease;
    will-change: transform;
    cursor: pointer;
    z-index: 1;
    text-align: center;
  }
  .bottombar .round i {
    line-height: 45px;
  }
}

.list-group .list-group-item {
  background: transparent;
}
</style>
