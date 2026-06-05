<template>
 
   
    <div class="content_lista">
      <FilterPerfumes :quantity-women="quantityWomen" :quantity-men="quantityMen" @filters-changed="updateFilters" :tiendas="tiendas"/>
      <ListCards :perfumes="filteredPerfumes" />
    </div>

</template>

<script>
import { getAllPerfumes, getAllStores,getPerfumesPage } from "@/services/api";
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
      tiendas: [],
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

    

    filteredPerfumes() {
      let result = [...this.perfumes];

      if (this.filters.genre != 'todos') {
        result = result.filter(
          p => p.target_public === this.filters.genre
        );
      }

     

      if (this.filters.webSite != 'todos') {
        result = result.filter(
          p => p.store_name === this.filters.webSite
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

     try {
      this.tiendas = await getAllStores();
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