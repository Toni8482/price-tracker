import { createRouter, createWebHistory } from "vue-router";
import ListaLibros from "../components/ListaLibros.vue";
import DetallesLibro from "@/components/DetallesLibro.vue";
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
    path: "/lista",
    name: "lista",
    component: ListaLibros,
  },
   {
    path: "/libro/:id",
    name: "detalle-libro",
    component: DetallesLibro,
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
