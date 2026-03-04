<template>
  <nav class="menu">
    <ul>
      <li><router-link to="/">HOME</router-link></li>
      <div>
        <form @submit.prevent="buscarPerfume">
          <input type="search" v-model="busqueda" placeholder="Buscar..." list="lista-perfumes" />

          <!-- DATALIST -->
          <datalist id="lista-perfumes">
            <option v-for="perfume in perfumes" :key="perfume.id" :value="perfume.nombre" />
          </datalist>
          <button type="submit" class="btn_buscar">
            <span>
              <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path
                  d="M22.658,21.28,17.9,16.522a9.558,9.558,0,1,0-1.424,1.4l4.768,4.768a1,1,0,1,0,1.414-1.414ZM3.049,10.513a7.5,7.5,0,1,1,7.5,7.5A7.509,7.509,0,0,1,3.049,10.513Z">
                </path>
              </svg>
            </span>
          </button>
        </form>
      </div>
      <li><router-link to="/perfumes">PERFUMES</router-link></li>
      <li><router-link to="/favoritos">🤍</router-link></li>
      <li><router-link to="/login">LOGIN</router-link></li>
      <li><router-link to="/users">USERS</router-link></li>
      <li><router-link to="/form-user">FORM USER</router-link></li>
     
     
    </ul>
  </nav>
</template>
<script>
import { getAllPerfumes } from "../services/api";


export default {
  name: "Menu",
  props: {

  },
  data() {
    return {
      perfumes: [],
       busqueda: ""
    }
  },
  computed: {},
  methods: {

    buscarPerfume() {
      if (!this.busqueda) return

      // Redirige pasando el nombre como query
      this.$router.push({
        path: "/perfumes",
        query: { nombre: this.busqueda }
      })

      this.busqueda = ""
    }




  },
  async mounted() {
    try {
      this.perfumes = await getAllPerfumes();
      this.perfumes = this.perfumes.map(p => ({
        ...p,
        precioSeleccionado: {
          precio: p.precio_contenido?.[0]?.precio || 0,
          image_url_precio_contenido: p.precio_contenido?.[0].image_url_precio_contenido || "",
        },
      }));

    } catch (error) {
      console.error(error);
    }
  },
}
</script>

<style scoped>
/* Contenedor del menú */
.menu {
  background-color: blueviolet;
  padding: 10px 20px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Lista horizontal */
.menu ul {
  display: flex;
  gap: 20px;
  margin: 0;
  padding: 0;
  list-style: none;
  justify-content: center;
  /* Centrar los enlaces */
  align-items: center;
}

/* Cada enlace */
.menu li {}

/* Estilo de router-link */
.menu a {
  color: white;
  text-decoration: none;
  font-weight: bold;
  padding: 8px 16px;
  border-radius: 8px;
  transition: all 0.3s ease;
  background-color: rgba(255, 255, 255, 0.1);
}

/* Hover y estado activo */
.menu a:hover {
  background-color: rgba(255, 255, 255, 0.3);
  transform: scale(1.05);
}

/* Link activo (resalta la página actual) */
.menu a.router-link-active {
  background-color: white;
  color: blueviolet;
}

.btn_buscar {

  width: 24px;
  height: 24px;
  cursor: pointer;
}
</style>