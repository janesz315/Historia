import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import { useAuthStore } from "@/stores/useAuthStore.js";


function checkIfNotLogged(to, from, next) {
  const store = useAuthStore();

  if (!store.user) {
    return next("/bejelentkezes"); // Ha nincs bejelentkezve, átirányít a bejelentkezési oldalra
  }

  // Ha admin jogosultságú oldalra próbál belépni a felhasználó, és nem admin
  if (to.meta.requiresAdmin && store.roleId !== 1) {
    return next("/"); // Ha nem admin, átirányítás a kezdőlapra
  }

  next(); // Ha minden rendben, folytatás
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
      meta: { title: (route) => 'Kezdőlap' }
    },
    {
      path: '/rolunk',
      name: 'about',
      component: () => import('../views/AboutView.vue'),
      meta: { title: (route) => 'Rólunk' }
    },
    {
      path: "/:pathMatch(.*)*",
      name: "NotFound",
      component: HomeView,
      meta: { title: (route) => 'Kezdőlap' }
    },
    {
      path: '/bejelentkezes',
      name: 'login',
      component: () => import('@/components/Auth/Login.vue'),
      meta: { title: (route) => 'Bejelentkezés' }
    },
    {
      path: '/regisztracio',
      name: 'signup',
      component: () => import('@/components/Auth/SignUp.vue'),
      meta: { title: (route) => 'Regisztráció' }
    },
    {
      path: '/profil',
      name: 'profile',
      component: () => import('@/components/Auth/Profile.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Profilom' }
    },
    {
      path: '/temakorok',
      name: 'categories',
      component: () => import('../views/CategoriesView.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Témakörök' }
    },
    {
      path: '/temakorokadmin',
      name: 'categoriesadmin',
      component: () => import('../views/CategoriesAdminView.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Témakörök - Admin', requiresAdmin: true, }
    },
    {
      path: '/tesztek',
      name: 'tests',
      component: () => import('../views/TestsView.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Tesztek' }
    },
    {
      path: '/admin',
      name: 'admin',
      component: () => import('../views/AdminView.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Admin felület', requiresAdmin: true, }
    },
    {
      path: '/kerdestipusok',
      name: 'questionTypes',
      component: () => import('../views/QuestionTypesView.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Kérdéstípusok', requiresAdmin: true, }
    },
    {
      path: '/kerdesek',
      name: 'kerdesek',
      component: () => import('../views/QuestionsAnswersView.vue'),
      beforeEnter: [checkIfNotLogged],
      meta: { title: (route) => 'Kérdésbank', requiresAdmin: true, }
    },
  ],
})

router.beforeEach((to, from, next) => {
  const title = to.meta.title;
  document.title = "Historia - " + to.meta.title(to);
  next();
})
export default router
