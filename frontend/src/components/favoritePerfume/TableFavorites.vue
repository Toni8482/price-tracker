<template>
  <BaseTable v-if="perfumesFavoritos.length > 0">
    <template #header>
      <tr>
        <th>image</th>
        <th>Marca</th>
        <th>Nombre</th>
        <th>Contenido</th>

        <th>Tienda</th>

        <th>Precio</th>
       
        <th></th>

      </tr>
    </template>
    <template #body>
      <tr v-for="perfume in perfumesFavoritos">
        <td><button @click="detallePerfume(perfume.id)">
            <img :src="perfume.image_url_precio_contenido" />
          </button>
        </td>
        <td>{{ perfume.marca }}</td>
        <td>{{ perfume.nombre }}</td>
        <td>{{ perfume.contenido }}</td>
        <td>{{ perfume.store_name }}</td>
        <td>{{ perfume.precio }} €</td>

       
        <td><button class="btn-delete"  @click="eliminarFavorito(perfume.id_variable)">Eliminar</button></td>




      </tr>
    </template>
  </BaseTable>
  <div v-else>
    <p>No existen perfumes favoritos</p>
  </div>
</template>

<script>
import BaseTable from '@/components/base/BaseTable.vue';

export default {
  name: 'TableFavorites',
  components: {
    BaseTable
  },
  props: {
    perfumesFavoritos: Array
  },
  data() {
    return {

    }
  },

  computed: {

  },

  methods: {
    detallePerfume(id) {
      this.$router.push({ name: "detalle-perfume", params: { id } });
    },
    eliminarFavorito(id) {
      this.$emit("id-delete", id);
    }
  },

  mounted() {

  }
}
</script>

<style scoped>
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

td {
  background: transparent;
  border-bottom: 1px solid rgba(212, 175, 55, 0.1);
  padding: 15px 10px;
  vertical-align: middle;
  transition: all 0.3s ease;
  color: var(--semiyelow-text);
  font-family: 'Montserrat', sans-serif;
}

tr:hover td {
  background: rgba(212, 175, 55, 0.08);
  color: var(--semiyelow-text);
}

td img {
  width: 70px;
  height: 70px;
  object-fit: contain;
  border-radius: 12px;
  background: rgba(0, 0, 0, 0.3);
  padding: 8px;
  transition: all 0.3s ease;
}

td img:hover {
  transform: scale(1.05);
}

td img[alt="Logo tienda"] {
  width: 100%;
  height: 100%;
  background: var(--store-card-bg);
  cursor: pointer;

}





button {
  margin: 5px 8px 5px 0;
  background-color: transparent;
  border: none;


  cursor: pointer;

  border-radius: 12px;
  transition: all 0.3s ease;

  position: relative;
  overflow: hidden;
}



button:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 30px rgba(212, 175, 55, 0.4);

}

button:active {
  transform: translateY(1px);
}
.btn-delete{
  background-color: var(--btn-bg);
  color: var(--btn-text);
  padding: 10px;
}
p{
  text-align: center;
}
</style>