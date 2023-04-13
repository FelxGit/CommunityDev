<template>
  <div class="post-form-create">
    <PostForm></PostForm>
  </div>
</template>
<script>
import PostForm from '../../components/PostForm.vue'
import { getters, mutations, actions } from "../../store";

export default {
    components: { PostForm },
    data() {
        return {
        };
    },
    mounted() {
      console.log('mounted postcreate')
    },
    computed: {
      ...getters
    }
}
</script>
