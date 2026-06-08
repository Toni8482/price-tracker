<template>
  <div class="">
    <h1>Favoritos</h1>
    <div class="table-wrapper">
      <TableFavorites class="table" :perfumes-favoritos="perfumesFavoritos" @id-delete="eliminarFavorito" />
    </div>
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
  margin-bottom: 20px;
}
.table{
  width: 90%;
  margin: auto;
}

.table-wrapper {

 
  padding: 5px;
  width: 100%;

}

@media (max-width: 768px) {
  .table-wrapper {

    overflow-x: auto;
  }
}

@media (max-width: 480px) {}
</style>