<template>
  <h1>Favoritos</h1>

  <table>

    <tr>
      <th>image</th>
      <th>Marca</th>
      <th>Nombre</th>
      <th>Contenido</th>

      <th>Tienda</th>

      <th>Precio</th>
      <th></th>
      <th></th>

    </tr>
    <tr v-for="perfume in perfumesFavoritos">
      <td><img :src="perfume.image_url_precio_contenido" /></td>
      <td>{{ perfume.marca }}</td>
      <td>{{ perfume.nombre }}</td>
      <td>{{ perfume.contenido }}</td>
      <td>{{ perfume.store_name }}</td>
      <td>{{ perfume.precio }}</td>

      <td><button @click="detallePerfume(perfume.id)">Ver detallle</button></td>
      <td><button @click="eliminarFavorito(perfume.id_variable)">Eliminar</button></td>




    </tr>
  </table>
</template>
<script>
import { eliminarFavorito, getFavoritosUser, getFavoritosVariableUser } from "../services/api";


export default {
  name: "FavoritosComponent",
  props: {

  },
  data() {
    return {
      perfumesFavoritos: null,
      token: null,
      userId: null,
    }
  },

  methods: {
    detallePerfume(id) {
      this.$router.push({ name: "detalle-perfume", params: { id } });
    },
  async eliminarFavorito(id) {
     await eliminarFavorito(this.token, id);
     alert(`Perfume con id ${id} eliminado `);


     this.perfumesFavoritos = await getFavoritosVariableUser(this.token);
    }
  },
  async mounted() {
    this.userId = localStorage.getItem('id');
    this.token = localStorage.getItem('token');


    this.perfumesFavoritos = await getFavoritosVariableUser(this.token);
  },
}
</script>

<style scoped>
/* ================================
   TABLA
================================ */
h1 {
  text-align: center;
  color: var(--table-text);
}

table {
  width: 90%;
  max-width: 900px;
  margin: 30px auto;
  border-collapse: collapse;
  background: var(--table-bg);
  backdrop-filter: blur(10px);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
  color: var(--table-text);
  display: table;
}

/* CABECERA */
th {
  padding: 12px 20px;
  background: var(--table-header-bg);
  color: var(--table-header-text);
  font-weight: bold;
  border-bottom: 2px solid rgba(255, 255, 255, 0.2);
  text-align: center;
}

/* CELDAS */
td {
  padding: 10px 15px;
  text-align: center;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  border: 1px solid var(--table-border);
}

/* FILAS IMPARES Y PARES */
tr:nth-child(odd) td {
  background: var(--table-row-odd);
}

tr:nth-child(even) td {
  background: var(--table-row-even);
}

/* HOVER DE FILAS */
tr:hover td {
  background: var(--table-row-hover);
  transition: background 0.2s;
}

/* RESPONSIVE */
@media (max-width: 768px) {
  table {
    width: 100%;
  }

  th,
  td {
    padding: 8px 10px;
  }
}
</style>