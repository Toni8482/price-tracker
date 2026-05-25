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
/* ================================
   BUSCADOR PREMIUM
================================ */

.busqueda {
  display: flex;
  align-items: center;
  gap: 8px;
  position: relative;
}

.busqueda input[type="search"] {
  background: rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(212, 175, 55, 0.3);
  border-radius: 50px;
  padding: 10px 18px;
  width: 250px;
  color: #f5e6d3;
  font-family: 'Montserrat', sans-serif;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  outline: none;
}

.busqueda input[type="search"]:focus {
  border-color: #d4af37;
  box-shadow: 0 0 15px rgba(212, 175, 55, 0.3);
  width: 300px;
  background: rgba(255, 255, 255, 0.12);
}

.busqueda input[type="search"]::placeholder {
  color: rgba(245, 230, 211, 0.5);
  font-family: 'Montserrat', sans-serif;
  font-size: 0.85rem;
}

/* Datalist personalizado (solo afecta al dropdown) */
.busqueda input[list]::-webkit-calendar-picker-indicator {
  filter: invert(1) brightness(0.8);
  cursor: pointer;
  opacity: 0.6;
  transition: opacity 0.3s ease;
}

.busqueda input[list]::-webkit-calendar-picker-indicator:hover {
  opacity: 1;
}

/* Botón buscar premium */
.btn_buscar {
  background: linear-gradient(135deg, #d4af37, #b8942e);
  border: none;
  width: 42px;
  height: 42px;
  border-radius: 50px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
  position: relative;
  overflow: hidden;
}

.btn_buscar::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
  transition: left 0.4s ease;
}

.btn_buscar:hover::before {
  left: 100%;
}

.btn_buscar:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(212, 175, 55, 0.4);
  background: linear-gradient(135deg, #e6c856, #c4a22a);
}

.btn_buscar:active {
  transform: translateY(1px);
}

.btn_buscar span {
  font-size: 1.1rem;
  filter: drop-shadow(0 1px 1px rgba(0, 0, 0, 0.2));
}

/* ================================
   RESPONSIVE BUSCADOR
================================ */
@media (max-width: 768px) {
  .busqueda input[type="search"] {
    width: 160px;
    padding: 8px 14px;
    font-size: 0.8rem;
  }

  .busqueda input[type="search"]:focus {
    width: 200px;
  }

  .btn_buscar {
    width: 36px;
    height: 36px;
  }

  .btn_buscar span {
    font-size: 0.9rem;
  }
}

@media (max-width: 480px) {
  .busqueda input[type="search"] {
    width: 120px;
  }

  .busqueda input[type="search"]:focus {
    width: 150px;
  }
}

</style>