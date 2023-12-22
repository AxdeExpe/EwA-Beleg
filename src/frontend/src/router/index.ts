import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import AboutView from '../views/AboutView.vue'
import LoginButtonVue from '../components/LoginButton.vue'
import Katalog from '../components/KatalogPage.vue'
import Warenkorb from '../components/WarenkorbPage.vue'
import Register from '../components/RegisterPage.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/about',
      name: 'about',
      component: AboutView
    },
    {
      path: '/katalog',
      name: 'katalog',
      component: Katalog
    },
    {
      path: '/warenkorb',
      name: 'warenkorb',
      component: Warenkorb
    },
    {
      path: '/login',
      name: 'login',
      component: LoginButtonVue
    },
    {
      path: '/register',
      name: 'register',
      component: Register
    }
  ]
});

export default router;
