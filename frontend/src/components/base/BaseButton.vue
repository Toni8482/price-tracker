<template>
  <div class="button-container">
    <div class="cards">
      <div v-for="perfume in perfumesDestacados" :key="perfume.id" class="card">
        <img :src="obtenerPrecioSeleccionado(perfume)?.image_url_precio_contenido" />
        <p class="marca">{{ perfume.marca }}</p>
        <p class="nombre">{{ perfume.nombre }}</p>
        <p class="precio"> {{ obtenerPrecioSeleccionado(perfume)?.precio || 0 }} € €</p>
        <button @click="verDetalle(perfume.id)">Ver detalles</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'BaseButton',
  props: {

  },
  data() {
    return {

    }
  },

  computed: {
  
  },

  methods: {
 
  },

  mounted() {

  }
}
</script>

<style scoped>

</style>