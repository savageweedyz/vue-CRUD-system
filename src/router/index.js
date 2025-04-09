import { createRouter, createWebHashHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/about',
    name: 'about',
    component: () => import(/* webpackChunkName: "about" */ '../views/AboutView.vue')
  },
  {
    path: '/customer',
    name: 'customer',
    component: () => import(/* webpackChunkName: "customer" */ '../views/CustomerView.vue')
  },
  {
    path: '/department',
    name: 'department',
    component: () => import(/* webpackChunkName: "customer" */ '../views/DepartmentView.vue')
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
