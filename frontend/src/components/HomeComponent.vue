<template>
  <div class="home">

    <!-- Banner de bienvenida -->
    <div class="banner">
      <h1>Bienvenido a PerfumeScraper</h1>
      <p>Encuentra tus perfumes favoritos al mejor precio</p>


    </div>

    <!-- Estadísticas rápidas -->
    <div class="stats">
      <div class="stat-card">
        <h2>{{ perfumes.length }}</h2>
        <p>Perfumes disponibles</p>
      </div>
      <div class="stat-card">
        <h2>{{ tiendas }}</h2>
        <p>Tiendas</p>
      </div>
      <div class="stat-card">
        <h2 v-if="perfumeMasCaro">
          {{ obtenerPrecioSeleccionado(perfumeMasCaro)?.precio || 0 }}€
        </h2>
        <h2 v-else>
          0€
        </h2>
        <p>Perfume más caro</p>
      </div>
    </div>

    <!-- Perfumes destacados -->
    <div class="destacados">
      <h2>Perfumes destacados</h2>
      <div class="cards">
        <div v-for="perfume in perfumesDestacados" :key="perfume.id" class="card">
         <img :src="obtenerPrecioSeleccionado(perfume)?.image_url_precio_contenido" />
          <p class="marca">{{ perfume.marca }}</p>
          <p class="nombre">{{ perfume.nombre }}</p>
          <p class="precio">  {{ obtenerPrecioSeleccionado(perfume)?.precio || 0 }} € €</p>
          <button @click="verDetalle(perfume.id)">Ver detalles</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import { getAllPerfumes } from "../services/api";


export default {
  name: "Home",
  data() {
    return {
      busqueda: "",
      tiendas: 2,
      perfumes: [],
    };
  },
  computed: {
    perfumesDestacados() {
      return [...this.perfumes]
        .sort((a, b) => {
          const precioA = this.obtenerPrecioSeleccionado(a)?.precio || 0;
          const precioB = this.obtenerPrecioSeleccionado(b)?.precio || 0;

          return precioB - precioA;
        })
        .slice(0, 4);
    },
    perfumeMasCaro() {
      return this.perfumes.reduce((max, p) => {
        const precioMax =
          this.obtenerPrecioSeleccionado(max)?.precio || 0;

        const precioActual =
          this.obtenerPrecioSeleccionado(p)?.precio || 0;

        return precioActual > precioMax ? p : max;
      }, this.perfumes[0]);
    },
  },
  methods: {
    buscarPerfume() {
      alert(`Buscando perfumes que contengan: "${this.busqueda}"`);
      // Aquí iría la lógica para filtrar o navegar a la lista de perfumes
    },
    verDetalle(id) {
      alert(`Ir al detalle del perfume con ID: ${id}`);
      // Aquí iría la navegación real: this.$router.push({name:'detalle-perfume', params:{id}})
    },
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
  },

};
</script>

<style scoped>
/* ================================
   HOME
================================ */
.home {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px;
  color: var(--text-color);
}

/* ================================
   BANNER
================================ */
.banner {
  width: 90%;
  max-width: 900px;
  background: var(--banner-bg);
  color: var(--banner-text);
  border-radius: 20px;
  padding: 50px 20px;
  text-align: center;
  box-shadow: 0 10px 30px rgba(124, 58, 237, 0.4);
  margin-bottom: 30px;
}

.banner h1 {
  font-size: 2.5rem;
  margin-bottom: 10px;
}

.banner p {
  font-size: 1.2rem;
  margin-bottom: 20px;
}

.banner .search {
  display: flex;
  justify-content: center;
  gap: 10px;
  flex-wrap: wrap;
}

.banner input {
  padding: 10px;
  border-radius: 8px;
  border: none;
  min-width: 200px;
  background: var(--banner-input-bg);
  color: var(--banner-input-color);
}

.banner input::placeholder {
  color: rgba(255, 255, 255, 0.6);
}

.banner button {
  padding: 10px 20px;
  border-radius: 8px;
  border: none;
  background: var(--btn-bg);
  color: var(--banner-text);
  font-weight: bold;
  cursor: pointer;
  transition: all 0.2s;
}

.banner button:hover {
  background: var(--btn-hover);
}

/* ================================
   ESTADÍSTICAS
================================ */
.stats {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
  justify-content: center;
  margin-bottom: 30px;
}

.stat-card {
  background: var(--stat-bg);
  backdrop-filter: blur(10px);
  color: var(--stat-text);
  padding: 20px;
  border-radius: 15px;
  text-align: center;
  width: 180px;
  border: 1px solid rgba(124, 58, 237, 0.3);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
}

.stat-card h2 {
  font-size: 2rem;
  margin: 0;
  color: var(--stat-title);
}

.stat-card p {
  margin: 5px 0 0 0;
}

/* ================================
   DESTACADOS / CARDS
================================ */
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

.card {
  background: var(--card-bg);
  backdrop-filter: blur(10px);
  border-radius: 15px;
  padding: 15px;
  text-align: center;
  width: 200px;
  border: 1px solid var(--card-border);
  box-shadow: 0 10px 25px var(--card-shadow);
  display: flex;
  flex-direction: column;
  align-items: center;
  transition: transform 0.25s, box-shadow 0.25s;
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

.card button {
  margin-top: 10px;
  background: var(--btn-bg);
  color: var(--banner-text);
  border: none;
  padding: 8px 15px;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
}

.card button:hover {
  background: var(--btn-hover);
}

/* ================================
   RESPONSIVE
================================ */
@media(max-width: 768px) {

  .cards,
  .stats {
    flex-direction: column;
    align-items: center;
  }
}
</style>