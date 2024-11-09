import { createRouter, createWebHistory } from 'vue-router';

// Ліниве завантаження компонентів
const HomePage = () => import('@/components/HomePage.vue');
const ManagementPage = () => import('@/components/ManagementPage.vue');
const AddAccountPage = () => import('@/components/AddAccount.vue');
const Login = () => import('@/components/Login.vue'); 

const routes = [
  {
    path: '/',
    name: 'Login',
    component: Login,
    meta: { requiresGuest: true } // Додано нове мета-поле
  },
  {
    path: '/home',
    name: 'HomePage',
    component: HomePage,
    meta: { requiresAuth: true }
  },
  {
    path: '/management',
    name: 'ManagementPage',
    component: ManagementPage,
    meta: { requiresAuth: true }
  },
  {
    path: '/add_account',
    name: 'AddAccountPage',
    component: AddAccountPage,
    meta: { requiresAuth: true }
  }
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
});

// Охорона маршрутів
router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('isAuthenticated');

  if (to.meta.requiresAuth && !isAuthenticated) {
    // Якщо маршрут вимагає авторизації, але користувач не авторизований
    next('/');
  } else if (to.meta.requiresGuest && isAuthenticated) {
    // Якщо користувач вже авторизований, не дозволяємо доступ до сторінки логіну
    next('/home');
  } else {
    next();
  }
});

export default router;
