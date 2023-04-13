<template>
  <div>
    <div class="loading"></div>
    <clip-loader
      v-show="type == loader.type.isByTask && withLoading"
      class="loaderCenter grid content-center"
      :loading="withLoading"
      :color="loader.color.bLink"
      :size="loader.size"
    ></clip-loader>
    <img
      v-show="type == loader.type.isByPage && withLoading"
      class="h-12 loaderCenter"
      style="z-index: 9999"
      src="../../assets/chronostep.png"
    />
  </div>
</template>

<script>
import Vue from "vue";
import { getters, mutations, actions } from "../store";

export default {
  props: {
    type: {
      type: String,
      default: "by-task", // by-page
    },
    withLoading: false,
  },
  data() {
    return {
      //
    };
  },
  beforeMount() {},
  computed: {
    ...getters,
  },
  methods: {
    ...mutations,
    ...actions,
  },
};
</script>

<style scoped lang="scss">
@import "../../sass/imports";
.loading {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgb(24, 25, 26, 1);
  z-index: $z-index-level-6;
}
</style>
