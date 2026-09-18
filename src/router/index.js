import { createRouter, createWebHistory } from 'vue-router'
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
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/AboutView.vue')
  },
  {
    path: '/customer',
    name: 'customer',
    component: () => import( '../views/customer.vue')
  },
  {
    path: '/employee',
    name: 'employee',
    component: () => import( '../views/employee.vue')
  },
  {
    path: '/Add_customer',
    name: 'Add_customer',
    component: () => import( '../views/Add_customer.vue')
  },
  {
    path: '/Add_employee',
    name: 'Add_employee',
    component: () => import( '../views/Add_employee.vue')
  },
  {
    path: '/Add_contact',
    name: 'Add_contact',
    component: () => import( '../views/Add_contact.vue')
  },
  {
    path: '/contacts',
    name: 'contacts',
    component: () => import( '../views/Contact.vue')
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
