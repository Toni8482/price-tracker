<template>
  <div class="home">
    <HeroSection />

    <StatsSection :most-expensive-perfume="mostExpensivePerfume" :quantity-perfumes="perfumes.length"
      :quantity-stores="tiendas.length" />

    <FeaturedPerfumes :featured-perfumes="featuredPerfumes" />

  </div>
</template>

<script>
import { getAllPerfumes, getAllStores } from "@/services/api";
import HeroSection from "@/components/home/HeroSection.vue";
import StatsSection from "@/components/home/StatsSection.vue";
import FeaturedPerfumes from "@/components/home/FeaturedPerfumes.vue";


export default {
  name: 'HomeView',
  components: {
    HeroSection,
    StatsSection,
    FeaturedPerfumes
  },
  data() {
    return {
      busqueda: "",

      perfumes: [],
      tiendas: []
    }
  },

  computed: {

    featuredPerfumes() {
      return [...this.perfumes]
        .filter(
          p => p.precio_contenido && p.precio_contenido.length > 0
        )
        .sort(() => Math.random() - 0.5)
        .slice(0, 4);

    },
    mostExpensivePerfume() {
      let max = 0;
      this.perfumes.forEach(perfume => {
        perfume.precio_contenido.forEach(p_c => {

          if (max < p_c.precio) {
            max = p_c.precio;
          }
        });
      });

      return max;
    },
  },

  methods: {
    obtenerPrecioSeleccionado(perfume) {
      return perfume.precio_contenido.find(
        p => p.id_contenido === perfume.precioSeleccionadoId
      );
    }
  },

  async mounted() {
    try {
      this.perfumes = await getAllPerfumes();
      this.perfumes = this.perfumes.map(p => ({
        ...p,
        precioSeleccionadoId: p.precio_contenido?.[0]?.id_contenido || null,
      }));

    } catch (error) {
      console.error(error);
    }

    try {
      this.tiendas = await getAllStores();
    } catch (error) {
      console.error(error);
    }
  }
}
</script>

<style scoped>
.home {
  display: flex;
  flex-direction: column;
  align-items: center;

  color: var(--text-color);

}
</style>