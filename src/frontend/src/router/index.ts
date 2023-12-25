import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import AboutView from '../views/AboutView.vue'
import LoginButtonVue from '../components/LoginPage/LoginButton.vue'
import Katalog from '../components/Einkauf/KatalogPage.vue'
import Warenkorb from '../components/Einkauf/WarenkorbPage.vue'
import Register from '../components/LoginPage/RegisterPage.vue'
import BuchDetail from '../components/Einkauf/BuchDetail.vue';
import Admin from '../components/AdminBereich/AdminBereich.vue';

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
      name: 'resgister',
      component: Register
    },
    {
      path: '/katalog/:title',
      name: 'buch-detail',
      component: BuchDetail
    },
    {
      path: '/admin',
      name: 'admin',
      component: Admin
    }
  ]
});

export default router;
