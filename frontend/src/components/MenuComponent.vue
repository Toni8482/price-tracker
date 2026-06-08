<template>
  <nav class="menu" :class="{ scrolled: isScrolled }">
    <div class="logo">
      <router-link to="/">
        <img :src="logoSrc" alt="Logo">
      </router-link>
    </div>

    <!--BUSCADOR DE PERFUME-->
    <div class="buscador-links">
      <form @submit.prevent="buscarPerfume">
        <div class="busqueda">
          <input type="search" v-model="busqueda" placeholder="Buscar..." list="lista-perfumes" />

          <!-- DATALIST -->
          <datalist id="lista-perfumes">
            <option v-for="perfume in perfumes" :key="perfume.id" :value="perfume.nombre" />
          </datalist>
          <button type="submit" class="btn_buscar">

            <img :src="lupa" alt="Lupa">

          </button>
        </div>
      </form>
      <ul>
        <li><router-link to="/">INICIO</router-link></li>
        <li><router-link to="/perfumes">PERFUMES</router-link></li>

        <div class="div-header" v-if="user.userName && user.userId">
          <li v-if="esAdmin"><router-link to="/users">USUARIOS</router-link></li>
        </div>
      </ul>
    </div>

    <div ref="userMenuMovil">
      <button class="btn-mobile" @click="showMobile = !showMobile"> ≡ </button>
      <div class=" dropdown-mobile" :class="{ open: showMobile }">

        <router-link to="/">INICIO</router-link>
        <router-link to="/perfumes">PERFUMES</router-link>

        <div class="div-header" v-if="esAdmin">
          <router-link to="/users">USERS</router-link>
        </div>

        <hr class="separator">
        <template v-if="user.userName">

          <router-link :to="{ name: 'user', params: { id: user.userId } }">
            MI PERFIL
          </router-link>

          <router-link :to="{ name: 'lista-favoritos', params: { id: user.userId } }">
            MIS FAVORITOS
          </router-link>

          <button @click="logout">
            CERRAR CUENTA
          </button>

        </template>

        <template v-else>

          <router-link to="/login">
            INICIAR SESION
          </router-link>

          <router-link to="/register">
            CREAR CUENTA
          </router-link>

        </template>
        <hr class="separator">
        <button class="theme-toggle" @click="toggleTheme"> {{ isDark ? '🌞 Tema claro' : '🌙 Tema oscuro' }}</button>
      </div>
    </div>
    <!--Botón de usuario-->
    <div class="user-menu" ref="userMenu">
      <button @click="showMenu = !showMenu" class=" btn_buscar">
        <span v-if="user.userName">
          {{ user.userName[0].toUpperCase() }}
        </span>

        <span v-else>
          <img :src="userSesion" alt="user">
        </span>
      </button>

      <div v-if="showMenu" class="dropdown">

        <template v-if="user.userName">

          <router-link :to="{ name: 'user', params: { id: user.userId } }">
            Mi perfil
          </router-link>

          <router-link :to="{ name: 'lista-favoritos', params: { id: user.userId } }">
            Mis favoritos
          </router-link>

          <button @click="logout">
            Cerrar cuenta
          </button>
          <button class="theme-toggle" @click="toggleTheme"> {{ isDark ? '🌞 Tema claro' : '🌙 Tema oscuro' }}</button>
        </template>

        <template v-else>

          <router-link to="/login">
            Iniciar sesión
          </router-link>

          <router-link to="/register">
            Crear cuenta
          </router-link>
          <button class="theme-toggle" @click="toggleTheme"> {{ isDark ? '🌞 Tema claro' : '🌙 Tema oscuro' }}</button>
        </template>

      </div>
    </div>
  </nav>
</template>
<script>
import { getAllPerfumes } from "../services/api";
import logoDark from '../assets/images/flower_white.svg'
import logoLight from '../assets/images/flower_black.svg'
import lupa from '../assets/images/lupa.svg'
import userSesion from '../assets/images/user_sin_sesion.svg'

export default {
  name: "Menu",
  props: {
    user: Object,
    esAdmin: Boolean
  },
  data() {
    return {
      perfumes: [],
      busqueda: "",
      isScrolled: false,
      isDark: false,
      showMenu: false,
      showMobile: false,
      lupa,
      userSesion
    }
  },
  computed: {
    logoSrc() {
      return this.isDark ? logoDark : logoLight
    },

  },

  methods: {
    logout() {

      this.$emit("logout");

    },

    buscarPerfume() {
      if (!this.busqueda) return;

      const perfume = this.perfumes.find(
        p => p.nombre.toLowerCase() === this.busqueda.toLowerCase()
      );

      if (perfume) {
        this.$router.push(`/perfume/${perfume.id}`);
      }

      this.busqueda = "";
    },
    toggleTheme() {
      this.isDark = !this.isDark;
      const root = document.documentElement;
      if (this.isDark) {
        root.classList.add('dark-theme');
        root.classList.remove('light-theme');
      } else {
        root.classList.add('light-theme');
        root.classList.remove('dark-theme');
      }
    },
    handleScroll() {
      this.isScrolled = window.scrollY > 50;
    },


    handleClickOutside(event) {
      const menu = this.$refs.userMenu;
      const menuMovil = this.$refs.userMenuMovil;

      if (menu && !menu.contains(event.target)) {
        this.showMenu = false;
      }

      if (menuMovil && !menuMovil.contains(event.target)) {
        this.showMobile = false;
      }
    },

  },
  async mounted() {
    try {
      this.perfumes = await getAllPerfumes();
    } catch (error) {
      console.error(error);
    }


    window.addEventListener('scroll', this.handleScroll);
    window.addEventListener('click', this.handleClickOutside);

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
  display: flex;
  background: var(--menu-bg);
  backdrop-filter: blur(10px);
  padding: 0.5rem 2.5rem;
  border-bottom: 1px solid var(--menu-border);
  justify-content: space-between;
  top: 0;
  z-index: 100;
  position: fixed;
  width: 100%;
  transition: padding 0.3s ease;
  align-items: center;
}

.menu.scrolled {
  padding: 0.3rem 2rem;
  /* header más pequeño al bajar */
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
.menu ul a:hover {
  background: var(--menu-link-hover-bg);
  color: var(--menu-link-hover-color);
  transform: translateY(-2px);
}

/* Link activo */
.menu ul a.router-link-active {
  background: var(--menu-link-active-bg);
  color: var(--menu-link-active-color);
  box-shadow: 0 0 10px rgba(212, 175, 55, 0.4);
}

.theme-toggle {
  background: none;
  border: none;

  cursor: pointer;
color:var(--menu-link);

}

.logo img {
  width: 50px;
  height: 50px;
  display: block;
  transition: all .3s ease;
}

.menu.scrolled .logo img {
  width: 40px;
  height: 40px;

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
  color: var(--btn-bg);
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
  color: var(--btn-bg);
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
  font-size: x-large;
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

.btn_buscar img {
  width: 18px;
  height: 18px;
  filter: var(--btn-icon-filter);
}

.buscador-links {
  display: flex;
  gap: 20px;
}



.user-menu {
  position: relative;
}

.dropdown {
  position: absolute;
  right: 0;
  top: 55px;
  display: flex;
  flex-direction: column;
  min-width: 180px;
  background: var(--card-bg);
  border: 1px solid var(--card-border);
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, .2);
  overflow: hidden;

}

.dropdown a,
.dropdown button {
  padding: 12px 16px;
  text-decoration: none;
  color: var(--text-color);
  background: transparent;
  border: none;
  text-align: left;
  cursor: pointer;
}

.dropdown a:hover,
.dropdown button:hover {
  background: rgba(212, 175, 55, .1);
}

.dropdown-mobile {
   max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease;
   display: flex;
  flex-direction: column;
  position: absolute;

   background: var(--card-bg);
  
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, .2);
  width: 100%;
  
  right: 0;
  top: 80px;
  align-items: center;
}

.dropdown-mobile.open{
 max-height: 500px;
}

.btn-mobile {
  display: none;
  background: linear-gradient(135deg, #d4af37, #b8942e);
  border: none;
  width: 42px;
  height: 42px;
  border-radius: 50px;
  cursor: pointer;

  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3);
  position: relative;
  overflow: hidden;
  font-size: x-large;

}

.separator {
  width: 80%;
  border: none;
  border-top: 1px solid rgba(212, 175, 55, 0.3);
  margin: 10px 0;
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

  .btn-mobile {
    display: block;
  }

  .buscador-links,
  .user-menu {
    display: none;
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