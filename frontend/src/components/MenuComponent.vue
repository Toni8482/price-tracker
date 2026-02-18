<template>
  <nav class="menu">
    <ul>
      <li><router-link to="/">Inicio</router-link></li>
      <li><router-link to="/lista">Lista</router-link></li>
      <li><router-link to="/perfumes">Lista perfumes</router-link></li>
    </ul>
  </nav>
</template>
<script>
export default {
  name: "Menu",
  props: {

  },
  data() {
    return {}
  },
  computed: {},
  methods: {}
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
</style>