import { createRouter, createWebHistory } from "vue-router";

import ListaPerfumes from "@/components/ListaPerfumes.vue";
import DetallesPerfumes from "@/components/DetallesPerfume.vue";
import Home from "@/components/HomeComponent.vue"

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
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
