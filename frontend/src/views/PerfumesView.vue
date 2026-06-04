<template>
 
   
    <div class="content_lista">
      <FilterPerfumes :quantity-women="quantityWomen" :quantity-men="quantityMen" @filters-changed="updateFilters" />
      <ListCards :perfumes="filteredPerfumes" />
    </div>

</template>

<script>
import { getAllPerfumes } from "@/services/api";
import FilterPerfumes from '@/components/listPerfumes/FilterPerfumes.vue';
import ListCards from '@/components/listPerfumes/ListCards.vue';



export default {
  name: 'PerfumesView',
  components: {
    FilterPerfumes,
    ListCards,

  },

  data() {
    return {
      perfumes: [],
      filters: {
        genre: 'todos',
        webSite: 'todos'
      }
    }
  },

  computed: {

    //CANTIDADES DE PERFUMES DE CADA GÉNERO//
    quantityWomen() {
      return this.perfumes.filter(
        perfume => perfume.target_public === "Mujer"
      ).length;
    },

    quantityMen() {
      return this.perfumes.filter(
        perfume => perfume.target_public === "Hombre"
      ).length;
    },

    
//################## MODIFICAR POR BUCLES ##################//
    filteredPerfumes() {
      let result = [...this.perfumes];

      if (this.filters.genre === 'Hombres') {
        result = result.filter(
          p => p.target_public === 'Hombre'
        );
      }

      if (this.filters.genre === 'Mujeres') {
        result = result.filter(
          p => p.target_public === 'Mujer'
        );
      }

      if (this.filters.webSite === 'perfumerias') {
        result = result.filter(
          p => p.store_name === 'Perfumerias'
        );
      }

      if (this.filters.webSite === 'perfumesClub') {
        result = result.filter(
          p => p.store_name === 'Perfumes Club'
        );
      }


      return result;
    }
  },

  methods: {
    updateFilters(filters) {
      this.filters = filters;
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
  }
}
</script>

<style scoped>
h1 {
  text-align: center;
  color: var(--text-color);
}



.content_lista {
   display: flex;
 
  gap: 20px;
}

</style>