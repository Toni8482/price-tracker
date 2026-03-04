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



export default {
  name: "FavoritosComponent",
  props: {

  },
  data() {
    return {
      perfumesFavoritos: [
        {
          "marca": "Dior",
          "nombre": "Sauvage",
          "concentracion": "Eau de Parfum",
          "tienda": "Sephora"
        },
        {
          "marca": "Chanel",
          "nombre": "Bleu de Chanel",
          "concentracion": "Parfum",
          "tienda": "El Corte Inglés"
        },
        {
          "marca": "Yves Saint Laurent",
          "nombre": "La Nuit de L'Homme",
          "concentracion": "Eau de Toilette",
          "tienda": "Douglas"
        },
        {
          "marca": "Armani",
          "nombre": "Acqua di Giò",
          "concentracion": "Eau de Parfum",
          "tienda": "Primor"
        }
      ]
    }
  },
  computed: {},
  methods: {



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