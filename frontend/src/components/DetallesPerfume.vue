<template>
  <div class="detalles-libro">
    <h1>Detalles de perfume</h1>

    <div v-if="perfume" class="card">
      <div class="imagen_card">
        <img :src="perfume.imagen_url" alt="Portada del libro" />
      </div>

      <div class="info">
        <img :src="perfume.store_logo">
        <p><strong>ID:</strong> {{ perfume.id }}</p>
        <p><strong>Título:</strong> {{ perfume.nombre }}</p>
        <p><strong>Precio:</strong> {{ perfume.precio }}€</p>



        <button @click="$router.back()">Volver a la lista</button><br>
        <button @click="abrirNuevaPestana(perfume.perfume_url)">Ir a tienda</button>
      </div>

      <div class="descripcion">
        <p v-html="perfume.descripcion"> </p>
      </div>


    </div>

    <div v-else>
      <p>Cargando...</p>
    </div>
    <div class="table_perfumes">
      <table v-if="perfumesComparados.length">
        <tr>
          <th>Imagen</th>
          <th>Marca</th>
          <th>Nombre</th>
          <th>Concentracion</th>
          <th>Contenido</th>
          <th>Precio</th>
          <th>Tienda</th>
          <th></th>
          <th></th>
        </tr>
        <tr v-for="perfume in this.perfumesComparados" :key="perfume.id">
          <td> <img :src="perfume.imagen_url" alt="Imagen perfume" /></td>
          <td>{{ perfume.marca }}</td>
          <td>{{ perfume.nombre }}</td>
          <td>{{ perfume.concentracion }}</td>
          <td>{{ perfume.contenido }}</td>
          <td>{{ perfume.precio }}
            €
          </td>

          <td><img :src="perfume.store_logo" alt="Imagen logo"></td>

          <td> <button @click="cargarPerfume(perfume.id)">Detalles</button></td>
          <td> <button @click="abrirNuevaPestana(perfume.perfume_url)">Ir a tienda</button></td>

        </tr>

      </table>



    </div>
  </div>



</template>

<script>

import { getPerfume } from "../services/api";
import { getAllPerfumes } from "../services/api";

export default {
  name: "DetallesPerfume",
  data() {
    return {
      perfume: null,
      perfumesComparados: [],
      perfumes: []
    };
  },
  watch: {
    '$route.params.id': {
      immediate: true,
      handler(newId) {
        this.cargarPerfume(newId)
      }
    }
  },
  methods: {
    PerfumesIguales() {

      let result = this.perfumes;

      this.perfumesComparados = result.filter(p => p.nombre.includes(this.perfume.nombre));



    },
    async cargarPerfume(id) {
      try {
        this.perfume = await getPerfume(id);

        this.PerfumesIguales()
      } catch (error) {
        console.error(error);
      }

    },

    abrirNuevaPestana(urlTienda) {
      window.open(urlTienda, "_blank", "noopener,noreferrer");
    }


  },
  async mounted() {


    try {
      this.perfumes = await getAllPerfumes();


    } catch (error) {
      console.error(error);
    }





    const id = this.$route.params.id;
    try {
      this.perfume = await getPerfume(id);

      this.PerfumesIguales()
    } catch (error) {
      console.error(error);
    }
  },
};
</script>

<style scoped>
.detalles-libro {
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

h1 {
  color: #4b0082;
  margin-bottom: 30px;
}

/* Tarjeta de detalle */
.card {
  display: flex;
  flex-direction: row;
  align-items: center;
  background: #a45ed8;
  padding: 20px;
  border-radius: 15px;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
  margin: 30px;
  width: 90%;
}

/* Imagen */
.imagen_card img {
  width: 100%;
  height: 500px;
  border-radius: 10px;
  margin-bottom: 20px;
  border: 2px solid #fff;
}

/* Info */

.info {


  width: 100%;

  text-align: center;
}

.info p {
  color: #201b1b;
  margin: 5px 0;
  font-size: 1rem;
  white-space: pre-line;
}

/* Botón volver */
button {
  margin-top: 15px;
  background-color: #4b0082;
  color: white;
  border: none;
  padding: 8px 20px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s, transform 0.2s;
}

button:hover {
  background-color: #6a1aa6;
  transform: scale(1.05);
}


table {

  border-collapse: collapse;

  width: 100%;

  text-align: center;
}

th {
  background-color: antiquewhite;
  font-size: 25px;
}

td {
  background-color: rgb(214, 213, 211);
}

td,
th {
  border: solid 3px;


  height: 100px;
  max-width: 120px;
}

td>img {
  height: 100px;
  border: solid black;
  width: 90%;
  object-fit: contain;

}

.table_perfumes {
  width: 100%;

  margin: 20px;
}
</style>
