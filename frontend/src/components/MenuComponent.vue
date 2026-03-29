<template>
  <nav class="menu">
    <ul>
      <li><router-link to="/">HOME</router-link></li>
      <div>
        <form @submit.prevent="buscarPerfume">
          <div class="busqueda">
            <input type="search" v-model="busqueda" placeholder="Buscar..." list="lista-perfumes" />

            <!-- DATALIST -->
            <datalist id="lista-perfumes">
              <option v-for="perfume in perfumes" :key="perfume.id" :value="perfume.nombre" />
            </datalist>
            <button type="submit" class="btn_buscar">
              <span>
                🔍
              
              </span>
            </button>
          </div>
        </form>
      </div>
      <li><router-link to="/perfumes">PERFUMES</router-link></li>



      <li><router-link to="/form-user">REGISTER</router-link></li>
      <div class="div-header" v-if="user.userName && user.userId">
        <li><router-link to="/users">USERS</router-link></li>
        <li><router-link :to="{ name: 'lista-favoritos', params: { id: user.userId } }">
            🤍
          </router-link></li>
        <li>

          <router-link :to="{ name: 'user', params: { id: user.userId } }">


            {{ user.userName }}
          </router-link>
        </li>
        <li> <button @click="logout">Logout</button></li>

      </div>
      <div v-else>
        <li><router-link to="/login">LOGIN</router-link></li>
      </div>
      <li>
        <button @click="toggleTheme">Cambiar tema</button>
      </li>
    </ul>
  </nav>
</template>
<script>
import { getAllPerfumes } from "../services/api";


export default {
  name: "Menu",
  props: {
    user: Object
  },
  data() {
    return {
      perfumes: [],
      busqueda: "",


    }
  },


  methods: {
    logout() {
      localStorage.removeItem('token');
      localStorage.removeItem('user_email');
      localStorage.removeItem('user_id');

      this.$router.push('/');


    },

    buscarPerfume() {
      if (!this.busqueda) return

      // Redirige pasando el nombre como query
      this.$router.push({
        path: "/perfumes",
        query: { nombre: this.busqueda }
      })

      this.busqueda = ""
    }, toggleTheme() {
      this.isDark = !this.isDark;
      const root = document.documentElement;
      if (this.isDark) {
        root.classList.add('dark-theme');
        root.classList.remove('light-theme');
      } else {
        root.classList.add('light-theme');
        root.classList.remove('dark-theme');
      }
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
.busqueda {
  display: flex;
}

.div-header {
  display: flex;
}

/* Contenedor del menú */
.menu {
  background: var(--menu-bg);
  backdrop-filter: blur(10px);
  padding: 10px 20px;
  border-bottom: 1px solid var(--menu-border);
  position: sticky;
  top: 0;
  z-index: 100;
}

/* Lista horizontal */
.menu ul {
  display: flex;
  gap: 20px;
  margin: 0;
  padding: 0;
  list-style: none;
  justify-content: center;
  align-items: center;
}

/* Estilo de enlaces */
.menu a {
  color: var(--menu-link);
  text-decoration: none;
  font-weight: 500;
  padding: 8px 16px;
  border-radius: 8px;
  transition: all 0.25s ease;
}

/* Hover */
.menu a:hover {
  background: var(--menu-link-hover-bg);
  color: var(--menu-link-hover-color);
  transform: translateY(-2px);
}

/* Link activo */
.menu a.router-link-active {
  background: var(--menu-link-active-bg);
  color: var(--menu-link-active-color);
  box-shadow: 0 0 10px rgba(124, 58, 237, 0.6);
}

/* Botón buscar */
.btn_buscar {
 
  width: 24px;
  height: 24px;
  cursor: pointer;
  filter: var(--btn-icon-filter);
  opacity: 0.8;
  transition: opacity 0.2s;
 
}

.btn_buscar:hover {
  opacity: 1;
}
</style>