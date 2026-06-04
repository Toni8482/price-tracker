<template>
  <div class="">
    <h1>Favoritos</h1>
    <TableFavorites :perfumes-favoritos="perfumesFavoritos" @id-delete="eliminarFavorito"/>
  </div>
</template>

<script>
import { eliminarFavorito, getFavoritosUser, getFavoritosVariableUser } from "../services/api";

import TableFavorites from '@/components/favoritePerfume/TableFavorites.vue';


export default {
  name: 'FavoriteView',
  components: {
    TableFavorites
  },
  data() {
    return {
      perfumesFavoritos: [],
      token: null,
      userId: null,
    }
  },

  computed: {

  },

  methods: {
 async eliminarFavorito(id) {
     await eliminarFavorito(this.token, id);
     alert(`Perfume con id ${id} eliminado `);


     this.perfumesFavoritos = await getFavoritosVariableUser(this.token);
    },
    
  },

  async mounted() {
    this.userId = localStorage.getItem('id');
    this.token = localStorage.getItem('token');


    this.perfumesFavoritos = await getFavoritosVariableUser(this.token);
  },
}
</script>

<style scoped>
h1 {
  text-align: center;
  color: var(--table-text);
}
</style>