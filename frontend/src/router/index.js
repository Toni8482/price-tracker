import { createRouter, createWebHistory } from "vue-router";

import ListaPerfumes from "@/components/ListaPerfumes.vue";
import DetallesPerfumes from "@/components/DetallesPerfume.vue";
import Home from "@/components/HomeComponent.vue"
import ListaUsers from "@/components/ListUsersComponent.vue"
import Login from "@/components/LoginComponent.vue"
import ListaFavoritos from "@/components/FavoritosComponent.vue"
import FormUser from "@/components/FormUserComponent.vue"
import DetalleUsuario from "@/components/DetallesUsuarios.vue";
import { authMiddleware } from '../middleware/auth.js';

const routes = [
  {
    path: "/",
    name: "Home",
    component: Home,
  },
 
  
   {
    path: "/perfumes",
    name: "lista-perfume",
    component: ListaPerfumes,
  },
   {
    path: "/perfume/:id",
    name: "detalle-perfume",
    component: DetallesPerfumes,
  },
   {
    path: "/users",
    name: "lista-users",
    component: ListaUsers,
  }, {
    path: "/login",
    name: "login",
    component: Login,
  }, {
    path: "/favoritos/:id",
    name: "lista-favoritos",
    component: ListaFavoritos,
  //  beforeEnter: authMiddleware,
  },
  {
    path: "/form-user",
    name: "form-user",
    component: FormUser,
  },

  {
    path: "/form-user/:id",
    name: "edit-user",
    component: FormUser,
  },
   {
    path: "/user/:id",
    name: "user",
    component: DetalleUsuario,
   // beforeEnter: authMiddleware,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
