<template>
  <div v-if="perfume" class="detalles-perfume">

    <DetailCard :perfume="perfume" :contenido-seleccionado-id="contenidoSeleccionadoId" @id-perfume="asignarPerfume"
      @cambiar-contenido="contenidoSeleccionadoId = $event" />
    <ComparisonTable :perfumes-comparados="PerfumesIguales" />
    <h2>
      Más perfumes de {{ perfume.marca }}
    </h2>
    <BrandCarrusel :perfumes-misma-marca="MismaMarca" />
  </div>
</template>

<script>
import DetailCard from "@/components/detailsPerfume/DetailCard.vue";
import {
  getPerfume,
  getAllPerfumes,
  addFavorito
} from "@/services/api";
import ComparisonTable from "@/components/detailsPerfume/ComparisonTable.vue";
import BrandCarrusel from "@/components/detailsPerfume/BrandCarrusel.vue";

export default {
  name: 'PerfumeView',
  components: {
    DetailCard,
    ComparisonTable,
    BrandCarrusel

  },
  data() {
    return {
      perfume: null,


      perfumes: [],
      contenidoSeleccionadoId: null,
      id: null,
      idVariable: null,
    }
  },
  watch: {


    $route: {
      immediate: true,
      async handler(route) {
        this.id = route.params.id;
        this.idVariable = route.params.idVariante;

        await this.cargarPerfume();
      }
    }
  },
  computed: {
    PerfumesIguales() {
      if (!this.perfume) return [];

      const filasComparacion = [];

      this.perfumes.forEach(p => {
        if (p.id === this.perfume.id) return;
        if (p.nombre.toLowerCase() !== this.perfume.nombre.toLowerCase()) return;

        p.precio_contenido?.forEach(contenido => {
          filasComparacion.push({
            id: p.id,
            id_contenido: contenido.id_contenido,
            marca: p.marca,
            nombre: p.nombre,
            concentracion: p.concentracion,
            formato: contenido.contenido,
            precio: contenido.precio,
            imagen: contenido.image_url_precio_contenido,
            store_logo: p.store_logo,
            perfume_url: p.perfume_url,
            web: p.store_name || p.fuente
          });
        });
      });

      const mejorPrecioPorFormato = {};

      filasComparacion.forEach(item => {
        if (
          !mejorPrecioPorFormato[item.formato] ||
          item.precio < mejorPrecioPorFormato[item.formato]
        ) {
          mejorPrecioPorFormato[item.formato] = item.precio;
        }
      });

      return filasComparacion
        .map(item => ({
          ...item,
          esMejorPrecio:
            item.precio === mejorPrecioPorFormato[item.formato]
        }))
        .sort((a, b) => a.precio - b.precio);
    },

    MismaMarca() {
      if (!this.perfume || !this.perfumes.length) return [];

      const marcaActual = this.perfume.marca
        ?.trim()
        .toLowerCase();

      return this.perfumes.filter(p =>
        p.id !== this.perfume.id &&
        p.marca &&
        p.marca.trim().toLowerCase() === marcaActual
      );
    }
  },

  methods: {
    async asignarPerfume(idContenido) {
      try {

        this.contenidoSeleccionadoId = Number(idContenido);

        const token = localStorage.getItem("token");

        await addFavorito(token, idContenido);

        alert("Perfume añadido a favoritos");
      } catch (error) {
        console.error(error);
      }
    },


    async cargarPerfume() {
      try {
        this.perfume = await getPerfume(this.id);

        if (
          this.idVariable &&
          this.perfume.precio_contenido.some(
            p => Number(p.id_contenido) === Number(this.idVariable)
          )
        ) {
          this.contenidoSeleccionadoId = Number(this.idVariable);
        } else if (this.perfume.precio_contenido?.length) {
          this.contenidoSeleccionadoId = Number(
            this.perfume.precio_contenido[0].id_contenido
          );
        }




      } catch (error) {
        console.error(error);
      }
    },
  },

  async mounted() {
    try {
      this.perfumes = await getAllPerfumes();

    } catch (error) {
      console.error(error);
    }
  }
}
</script>

<style scoped>
.detalles-perfume {

  display: flex;
  flex-direction: column;
  align-items: center;
  width: 100%;
  min-height: 100vh;
  position: relative;
  gap: 50px;
}



h1 {
  color: var(--semiyelow-text);
  margin-bottom: 40px;
  text-align: center;
  font-family: 'Cormorant Garamond', serif;
  font-size: 3rem;
  font-weight: 600;
  letter-spacing: 2px;
  position: relative;
  z-index: 1;
  text-transform: uppercase;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

h1::after {
  content: '✦';
  font-size: 1.5rem;
  color: #d4af37;
  margin: 0 15px;
  vertical-align: middle;
}

h1::before {
  content: '✦';
  font-size: 1.5rem;
  color: #d4af37;
  margin: 0 15px;
  vertical-align: middle;
}
</style>