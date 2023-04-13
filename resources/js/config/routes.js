import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

import LandingPage from '../pages/LandingPage.vue'
import userRoutes from '../pages/user'
import SidebarRoutes from '../pages/sidebar-views'
import authRoutes from '../pages/auth'
import postRoutes from '../pages/posts'

const routes = [
  {
    path: '/',
    name: 'landing-page',
    component: LandingPage,
    children: [
      ...SidebarRoutes,
      ...postRoutes
    ]
  },
  ...userRoutes,
  ...SidebarRoutes,
  ...authRoutes,
  ...postRoutes
];

const router = new Router({
  mode: 'history',
  routes
});

export default router