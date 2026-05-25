<template>
  <MenuComponent :user="userData"></MenuComponent>
  <router-view></router-view>

</template>

<script>


import MenuComponent from './components/MenuComponent.vue';


export default {
  name: "App",
  components: {

    MenuComponent
  },
  data() {
    return {
      userData: {
        userName: null,
        userId: null
      }
    };
  },
  watch: {
    $route() {
      this.loadUser();
    }
  },
  methods: {
    loadUser() {
      this.userData.userName = localStorage.getItem('user_email');
      this.userData.userId = localStorage.getItem('user_id');
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
</style>
