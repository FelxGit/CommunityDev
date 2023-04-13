import Profile from './Profile.vue'
import NotifList from './NotifList.vue'

const userRoutes = [
  {
    path: '/user/profile',
    component: Profile,
    name: 'profile',
    icon: 'fa fa-user',
    meta: {
      requiresAuth: true,
    }
  },
  {
    path: '/user/notification',
    component: NotifList,
    name: 'notif-list',
    meta: {
      requiresAuth: true,
    }
  }
];
export default userRoutes
