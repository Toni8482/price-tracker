import { createRouter, createWebHistory } from "vue-router";

import HomeView from "@/views/HomeView.vue";
import FavoriteView from "@/views/FavoriteView.vue";
import PerfumesView from "@/views/PerfumesView.vue";
import PerfumeView from "@/views/PerfumeView.vue";
import RegisterView from "@/views/RegisterView.vue";
import LoginView from "@/views/LoginView.vue";
import UsersView from "@/views/UsersView.vue";
import UserView from "@/views/UserView.vue";
import { authMiddleware } from '../middleware/auth.js';
import { adminMiddleware } from '../middleware/admin.js';

const routes = [
 
   {
    path: "/",
    name: "HomeView",
    component: HomeView,
  },
  {
    path: "/perfumes",
    name: "lista-perfume",
    component: PerfumesView,
  }, 
   {
    path: "/perfume/:id",
    name: "detalle-perfume",
    component: PerfumeView,
  },
    {
    path: "/perfume/:id/:idVariante",
    name: "detalle-perfume-variante",
    component: PerfumeView,
  },
   {
    path: "/users",
    name: "lista-users",
    component: UsersView,
     beforeEnter: authMiddleware,
     beforeEnter: adminMiddleware,
  }, 
  {
    path: "/login",
    name: "login",
    component: LoginView,
  },  
   {
    path: "/favoritos/:id",
    name: "lista-favoritos",
    component: FavoriteView,
    beforeEnter: authMiddleware,
  }, 
  {
    path: "/register",
    name: "register",
    component: RegisterView,
  },
  {
    path: "/register/:id",
    name: "edit-register",
    component: RegisterView,
     beforeEnter: adminMiddleware,
  }, 
   {
    path: "/user/:id",
    name: "user",
    component: UserView,
    beforeEnter: authMiddleware,
  },
];
const router = createRouter({
  history: createWebHistory(),
  routes,

  scrollBehavior() {
    return { top: 0 };
  }
});

export default router;
