<template>
 <div class="destacados">
    <h2>Perfumes destacados</h2>
    <div class="cards">
      <BaseCard v-for="perfume in featuredPerfumes" :key="perfume.id" class="card">
        <img :src="perfume.precio_contenido[0].image_url_precio_contenido" />
        <p class="marca">{{ perfume.marca }}</p>
        <p class="nombre">{{ perfume.nombre }}</p>
        <p class="precio"> {{ perfume.precio_contenido[0].precio || 0 }} €</p>
        <button @click="seeDetailPerfume(perfume.id, perfume.precio_contenido[0].id_contenido)">Ver detalles</button>
      </BaseCard>
    </div>
  </div>
</template>

<script>
import BaseCard from '@/components/base/BaseCard.vue';

export default {
  name: 'FeaturedPerfumes',
props:{
  featuredPerfumes : Object,
},
 components: {
    BaseCard
  },
  data() {
    return {
    
    }
  },

  computed: {
  
  },

  methods: {
   seeDetailPerfume(id, idVariant) {
     
     this.$router.push({
                name: "detalle-perfume", params: {
                    id: id,
                    idVariante:idVariant
                }
            });
    },
  },

  mounted() {
  
  }
}
</script>

<style scoped>
.destacados {
  width: 100%;
  max-width: 1000px;
  margin-bottom: 50px;
}

.destacados h2 {
  color: var(--stat-title);
  margin-bottom: 20px;
  text-align: center;
}

.cards {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
  justify-content: center;
}


.card:hover {
  transform: translateY(-6px);
  box-shadow: 0 15px 35px var(--card-shadow);
}

.card img {
  width: 100%;
  height: 250px;
  object-fit: cover;
  border-radius: 10px;
  margin-bottom: 10px;
}

.card .marca {
  color: var(--card-marca);
  font-weight: bold;
}

.card .nombre {
  font-weight: bold;
  color: var(--card-title);
  margin: 5px 0;
}

.card .precio {
  color: var(--card-price);
  font-weight: bold;
}
.card .marca,
.card .nombre {
   width: 100%;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.card button {
  margin: auto;
  background: var(--btn-bg);
  color: var(--btn-text);
  border: none;
  padding: 8px 15px;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
 width: 100%;
}

.card button:hover {
  background: var(--btn-hover);
}
</style>