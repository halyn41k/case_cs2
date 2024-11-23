import { createRouter, createWebHistory } from 'vue-router';

// Ліниве завантаження компонентів
const HomePage = () => import('@/components/HomePage.vue');
const ManagementPage = () => import('@/components/ManagementPage.vue');
const AddAccountPage = () => import('@/components/AddAccount.vue');

const routes = [
  {
    path: '/',
    name: 'HomePage',
    component: HomePage,
  },
  {
    path: '/management',
    name: 'ManagementPage',
    component: ManagementPage,
  },
  {
    path: '/add_account',
    name: 'AddAccountPage',
    component: AddAccountPage,
  }
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
});


export default router;
