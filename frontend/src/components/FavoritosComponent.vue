<template>
  <h1>Favoritos</h1>

  <table>

    <tr>
      <th>Marca</th>
      <th>Nombre</th>
      <th>Concentracion</th>

      <th>Tienda</th>
    </tr>
    <tr v-for="perfume in perfumesFavoritos">
      <td>{{ perfume.marca }}</td>
      <td>{{ perfume.nombre }}</td>
      <td>{{ perfume.concentracion }}</td>
      <td>{{ perfume.tienda }}</td>
    </tr>
  </table>
</template>
<script>
import {getFavoritosUser } from "../services/api";


export default {
  name: "FavoritosComponent",
  props: {

  },
  data() {
    return {
      perfumesFavoritos:null
    }
  },
   watch: {
  
    "$route.params.id": {
      immediate: true,
      handler(newId) {
        this.cargarUsuario(newId);

      },
    },
   
  },
  computed: {},
  methods: {

 async cargarUsuario(id){
        this.perfumesFavoritos = await getFavoritosUser (id);
          
    }

  },
  async mounted() {

  },
}
</script>

<style scoped>
h1 {
  text-align: center;
}

table {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  border-collapse: collapse;
}

th,
td {
  border: solid 3px;

}
</style>