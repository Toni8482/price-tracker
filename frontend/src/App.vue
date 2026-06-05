<template>
  <div id="app">
    <MenuComponent :user="userData" @logout="cerrarSesion" :es-admin="esAdmin" />

    <main class="main-content">
      <router-view />
    </main>

    <Footer />
  </div>
</template>

<script>


import MenuComponent from './components/MenuComponent.vue';
import Footer from './components/FooterComponent.vue';
import { logout, isAdmin } from "./services/api";

export default {
  name: "App",
  components: {

    MenuComponent,
    Footer
  },
  data() {
    return {
      userData: {
        userName: null,
        userId: null,
        roles: []
      }
    };
  },
  watch: {
    $route() {
      this.loadUser();
    }
  },
  computed: {
    esAdmin() {
      return this.userData.roles.includes("ROLE_ADMIN");
    }
  },
  methods: {
    cerrarSesion() {
      logout();
      this.$router.push('/');
      this.userData = {
        userName: "",
        userId: null,
        roles: []
      };
    },
    loadUser() {
      this.userData.userName = localStorage.getItem('user_email');
      this.userData.userId = localStorage.getItem('user_id');
      this.userData.roles = JSON.parse(localStorage.getItem('roles') || '[]');
    }
  },
  mounted() {
    this.loadUser();
  },

};
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background: var(--bg-color);
  color: var(--text-color);
  margin: 0;

  padding: 0;
  padding-top: 200px;
  transition: background 0.3s, color 0.3s;
  /* animación suave */
}

body::before {
  content: "";
  position: fixed;
  width: 500px;
  height: 500px;
  background: var(--bg-glow);
  filter: blur(200px);
  top: -200px;
  left: -200px;
  z-index: -1;
  transition: background 0.3s;
  /* animación suave al cambiar tema */
}

#app {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.main-content {
  flex: 1;
}
</style>
