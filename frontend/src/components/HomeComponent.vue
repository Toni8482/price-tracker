<template>
  <div class="home">

    <!-- Banner de bienvenida -->
    <div class="banner">
      <h1>Bienvenido a PerfumeScraper</h1>
      <p>Encuentra tus perfumes favoritos al mejor precio</p>

      <div class="search">
        <input v-model="busqueda" placeholder="Buscar perfume..." />
        <button @click="buscarPerfume">Buscar</button>
      </div>
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
        <h2>{{ perfumeMasCaro.precio }}€</h2>
        <p>Perfume más caro</p>
      </div>
    </div>

    <!-- Perfumes destacados -->
    <div class="destacados">
      <h2>Perfumes destacados</h2>
      <div class="cards">
        <div v-for="perfume in perfumesDestacados" :key="perfume.id" class="card">
          <img :src="perfume.imagen_url" />
          <p class="marca">{{ perfume.marca }}</p>
          <p class="nombre">{{ perfume.nombre }}</p>
          <p class="precio">{{ perfume.precio }} €</p>
          <button @click="verDetalle(perfume.id)">Ver detalles</button>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  name: "Home",
  data() {
    return {
      busqueda: "",
      tiendas: 2,
      perfumes: [
        { id: 1, nombre: "Aqua Vitae", marca: "Brand A", precio: 49, imagen_url: "https://via.placeholder.com/200x250?text=Perfume+1" },
        { id: 2, nombre: "Mystic Rose", marca: "Brand B", precio: 75, imagen_url: "https://via.placeholder.com/200x250?text=Perfume+2" },
        { id: 3, nombre: "Ocean Breeze", marca: "Brand C", precio: 65, imagen_url: "https://via.placeholder.com/200x250?text=Perfume+3" },
        { id: 4, nombre: "Velvet Night", marca: "Brand D", precio: 90, imagen_url: "https://via.placeholder.com/200x250?text=Perfume+4" },
        { id: 5, nombre: "Golden Oud", marca: "Brand E", precio: 120, imagen_url: "https://via.placeholder.com/200x250?text=Perfume+5" },
      ],
    };
  },
  computed: {
    perfumesDestacados() {
      // Devuelve los 4 perfumes con mayor precio
      return [...this.perfumes].sort((a,b) => b.precio - a.precio).slice(0,4);
    },
    perfumeMasCaro() {
      return this.perfumes.reduce((max,p) => p.precio > max.precio ? p : max, this.perfumes[0]);
    }
  },
  methods: {
    buscarPerfume() {
      alert(`Buscando perfumes que contengan: "${this.busqueda}"`);
      // Aquí iría la lógica para filtrar o navegar a la lista de perfumes
    },
    verDetalle(id) {
      alert(`Ir al detalle del perfume con ID: ${id}`);
      // Aquí iría la navegación real: this.$router.push({name:'detalle-perfume', params:{id}})
    }
  }
};
</script>

<style scoped>
/* Reset simple */
body { margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif; }

/* Contenedor Home */
.home { display: flex; flex-direction: column; align-items: center; padding: 20px; }

/* Banner */
.banner {
  width: 90%;
  max-width: 900px;
  background: linear-gradient(135deg, #a45ed8, #6a1aa6);
  color: white;
  border-radius: 20px;
  padding: 50px 20px;
  text-align: center;
  box-shadow: 0 6px 15px rgba(0,0,0,0.3);
  margin-bottom: 30px;
}

.banner h1 { font-size: 2.5rem; margin-bottom: 10px; }
.banner p { font-size: 1.2rem; margin-bottom: 20px; }

.banner .search { display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; }
.banner input {
  padding: 10px;
  border-radius: 8px;
  border: none;
  min-width: 200px;
}
.banner button {
  padding: 10px 20px;
  border-radius: 8px;
  border: none;
  background-color: #fff;
  color: #4b0082;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.2s;
}
.banner button:hover { background-color: #f0e6ff; }

/* Estadísticas */
.stats {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
  justify-content: center;
  margin-bottom: 30px;
}
.stat-card {
  background-color: #f8f0ff;
  color: #4b0082;
  padding: 20px;
  border-radius: 15px;
  text-align: center;
  width: 180px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}
.stat-card h2 { font-size: 2rem; margin: 0; }
.stat-card p { margin: 5px 0 0 0; }

/* Destacados */
.destacados { width: 100%; max-width: 1000px; margin-bottom: 50px; }
.destacados h2 { color: #4b0082; margin-bottom: 20px; text-align: center; }

.cards {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
  justify-content: center;
}

.card {
  background: #f8f0ff;
  border-radius: 15px;
  padding: 15px;
  text-align: center;
  width: 200px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.2);
  display: flex;
  flex-direction: column;
  align-items: center;
}

.card img {
  width: 100%;
  height: 250px;
  object-fit: cover;
  border-radius: 10px;
  margin-bottom: 10px;
}

.card .marca { color: #af3030; font-weight: bold; }
.card .nombre { font-weight: bold; color: #201b1b; margin: 5px 0; }
.card .precio { color: #4b0082; font-weight: bold; }

.card button {
  margin-top: 10px;
  background-color: #4b0082;
  color: white;
  border: none;
  padding: 8px 15px;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
}
.card button:hover { background-color: #6a1aa6; }

/* Responsive */
@media(max-width: 768px){
  .cards, .stats { flex-direction: column; align-items: center; }
}
</style>