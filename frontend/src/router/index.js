import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { useAuthStore } from "@/stores/useAuthStore.js";


function checkIfNotLogged() {
  const storeAuth = useAuthStore();
  if (!storeAuth.user) {
    return "/login";
  } else {
    
  }
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      meta: {title: (route) => 'Kezdőlap'}
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
      meta: {title: (route) => 'Rólunk'}
    },
    { path: "/:pathMatch(.*)*",
      name: "NotFound",
      component: HomeView,
      meta: {title: (route) => 'Kezdőlap'}
    },
  ],
})

router.beforeEach((to, from, next) => {
  const title = to.meta.title;
  document.title ="Historia - " + to.meta.title(to);
  next();
})
export default router
