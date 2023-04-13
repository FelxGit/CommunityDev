import Post from './Post.vue'
import PostCreate from './PostCreate.vue'

const post = [
  {
    path: '/posts/:id',
    name: 'post',
    component: Post,
    props: true,
    meta: {
      requiresAuth: true,
    }
  },
  {
    path: '/posts/create',
    name: 'post-create',
    component: PostCreate,
    meta: {
      requiresAuth: true,
    }
  }
];
export default post
