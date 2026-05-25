<template>
  <div class="detalles-libro">
    <h1>Detalles de perfume</h1>

    <div v-if="perfume">
      <!-- CARD -->
      <div class="card">
        <div class="imagen_card">
          <img :src="imagenCard" alt="Imagen perfume" />
        </div>

        <div class="info">
          <div>
            <img :src="perfume.store_logo" alt="Logo tienda" />
          </div>

          <h2>{{ perfume.marca }}</h2>
          <p>{{ perfume.nombre }}</p>
          <p>{{ perfume.concentracion }}</p>

          <p>Elige tamaño:</p>

          <label v-for="precioContenido in perfume.precio_contenido" :key="precioContenido.id_contenido">
            <input type="radio" v-model="contenidoSeleccionadoId" :value="Number(precioContenido.id_contenido)" />

            {{ precioContenido.contenido }}
            - {{ precioContenido.precio }} €

            <button @click="asignarPerfume(precioContenido.id_contenido)">
              🤍
            </button>
          </label>

          <button @click="$router.back()">
            Volver a la lista
          </button>

          <button @click="abrirNuevaPestana(perfume.perfume_url)">
            Ir a tienda
          </button>
        </div>
      </div>

      <!-- DESCRIPCIÓN -->
      <button @click="mostrarDescripcion = !mostrarDescripcion" class="toggle-desc">
        {{ mostrarDescripcion ? 'Ocultar descripción' : 'Ver descripción' }}
      </button>

      <div class="descripcion" :class="{ abierto: mostrarDescripcion }">
        <p v-html="perfume.descripcion"></p>
      </div>

      <!-- COMPARAR PRECIOS -->
      <h2>Comparar precios</h2>

      <div class="table_perfumes">
        <table v-if="perfumesComparados.length">
          <tr>
            <th>Imagen</th>
            <th>Marca</th>
            <th>Nombre</th>
            <th>Concentración</th>
            <th>Precio</th>
            <th>Tienda</th>
            <th></th>
            <th></th>
          </tr>

          <tr v-for="perfume in perfumesComparados" :key="perfume.id">
            <td>
              <img :src="perfume.precio_contenido?.[0]?.image_url_precio_contenido" alt="Imagen perfume" />
            </td>

            <td>{{ perfume.marca }}</td>

            <td>{{ perfume.nombre }}</td>

            <td>{{ perfume.concentracion }}</td>

            <td>
              {{ perfume.precio_contenido?.[0]?.precio || 0 }} €
            </td>

            <td>
              <img :src="perfume.store_logo" alt="Imagen logo" />
            </td>

            <td>
              <button @click="cargarPerfume(perfume.id)">
                Detalles
              </button>
            </td>

            <td>
              <button @click="abrirNuevaPestana(perfume.perfume_url)">
                Ir a tienda
              </button>
            </td>
          </tr>
        </table>

        <span v-else>
          No hay perfumes para comparar
        </span>
      </div>

      <!-- MISMA MARCA -->
      <h2>
        Más perfumes de {{ perfume.marca }}
      </h2>

      <div class="carrusel-container" v-if="perfumesMismaMarca.length">

        <swiper :slides-per-view="4" :space-between="20" :breakpoints="{
          320: {
            slidesPerView: 1
          },
          640: {
            slidesPerView: 2
          },
          1024: {
            slidesPerView: 4
          }
        }">

          <swiper-slide v-for="perfume in perfumesMismaMarca" :key="perfume.id">

            <div class="perfume-card">

              <img :src="perfume.precio_contenido?.[0]?.image_url_precio_contenido ||
                perfume.imagen_url
                " class="perfume-img" />

              <img :src="perfume.store_logo" class="logo-tienda">

              <h3>{{ perfume.nombre }}</h3>

              <p>{{ perfume.concentracion }}</p>

              <button @click="cargarPerfume(perfume.id)">
                Detalles
              </button>

            </div>

          </swiper-slide>

        </swiper>

      </div>
    </div>
  </div>
</template>

<script>

import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import {
  getPerfume,
  getAllPerfumes,
  addFavorito
} from "../services/api";

export default {
  name: "DetallesPerfume",

  data() {
    return {
      perfume: null,
      mostrarDescripcion: false,
      perfumesComparados: [],
      perfumesMismaMarca: [],
      perfumes: [],
      contenidoSeleccionadoId: null,
    };
  },
  components: {
    Swiper,
    SwiperSlide
  },
  watch: {
    perfumes: {
      deep: true,
      handler() {
        this.PerfumesIguales();
        this.MismaMarca();
      }
    },

    "$route.params.id": {
      immediate: true,
      async handler(newId) {
        await this.cargarPerfume(newId);

        // si perfumes ya está cargado
        if (this.perfumes.length) {
          this.PerfumesIguales();
          this.MismaMarca();
        }
      },
    },
  },

  computed: {
    imagenCard() {
      if (!this.perfume || !this.contenidoSeleccionadoId) {
        return "";
      }

      const precioSeleccionado =
        this.perfume.precio_contenido.find(
          p =>
            p.id_contenido ===
            this.contenidoSeleccionadoId
        );

      return (
        precioSeleccionado?.image_url_precio_contenido ||
        ""
      );
    },
  },

  methods: {
    PerfumesIguales() {
      if (!this.perfume) return;

      this.perfumesComparados = this.perfumes
        .filter(
          p =>
            p.id !== this.perfume.id &&
            p.nombre.toLowerCase() ===
            this.perfume.nombre.toLowerCase()
        )
        .sort((a, b) => {
          const precioA =
            a.precio_contenido?.[0]?.precio || 0;

          const precioB =
            b.precio_contenido?.[0]?.precio || 0;

          return precioA - precioB;
        });
    },

    MismaMarca() {
      if (!this.perfume || !this.perfumes.length) return;

      const marcaActual = this.perfume.marca
        ?.trim()
        .toLowerCase();

      this.perfumesMismaMarca = this.perfumes.filter(p => {
        return (
          p.id !== this.perfume.id &&
          p.marca &&
          p.marca.trim().toLowerCase() === marcaActual
        );
      });

      console.log(this.perfumesMismaMarca);
    },

    async cargarPerfume(id) {
      try {
        this.perfume = await getPerfume(id);

        if (this.perfume.precio_contenido?.length) {
          this.contenidoSeleccionadoId = Number(
            this.perfume.precio_contenido[0]
              .id_contenido
          );
        }
      } catch (error) {
        console.error(error);
      }
    },

    abrirNuevaPestana(urlTienda) {
      window.open(
        urlTienda,
        "_blank",
        "noopener,noreferrer"
      );
    },

    async asignarPerfume(id) {
      try {
        const token = localStorage.getItem("token");

        await addFavorito(token, id);

        alert("Perfume añadido a favoritos");
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
  },
};
</script>
<style scoped>
/* ================================
   DETALLES PERFUMES
================================ */
.detalles-libro {
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

h1 {
  color: var(--form-text);
  margin-bottom: 30px;
  text-align: center;
  font-family: 'Segoe UI', sans-serif;
}

/* Tarjeta principal */
.card {
  display: flex;
  flex-direction: row;
  background: var(--card-bg);
  color: var(--card-text);
  backdrop-filter: blur(10px);
  padding: 20px;
  border-radius: 15px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
  margin: 30px 0 10px;
  width: 90%;
  max-width: 900px;
  gap: 20px;
  align-items: flex-start;
  border: 1px solid var(--table-border);
}

/* Imagen del perfume */
.imagen_card {
  flex: 0 0 40%;
  height: 300px;
  border-radius: 12px;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--card-bg);
  border: 1px solid var(--table-border);
}

.imagen_card img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  border-radius: 12px;
  transition: transform 0.3s ease;
}

.imagen_card img:hover {
  transform: scale(1.05);
}

/* Info a la derecha */
.info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 10px;
  color: var(--card-text);
}

.info p {
  margin: 3px 0;
  font-size: 1rem;
}

.info img {
  height: 50px;
  margin-bottom: 10px;
}

/* Radio buttons estilo píldora */
.size-options {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin: 10px 0;
}

.size-options label {
  display: flex;
  align-items: center;
  gap: 5px;
  background: var(--pill-bg);
  color: var(--pill-text);
  padding: 6px 12px;
  border-radius: 20px;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.2s ease;
}

.size-options label:hover {
  background: var(--pill-hover);
}

.size-options input[type="radio"] {
  appearance: none;
  -webkit-appearance: none;
  width: 18px;
  height: 18px;
  border: 2px solid var(--pill-text);
  border-radius: 50%;
  cursor: pointer;
  position: relative;
}

.size-options input[type="radio"]:checked::before {
  content: '';
  display: block;
  width: 10px;
  height: 10px;
  background: var(--radio-checked);
  border-radius: 50%;
  margin: 2px;
}

/* Botones */
button {
  margin: 5px 0;
  background: var(--primary-color);
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.2s;
}

button:hover {
  background: var(--primary-hover);
  transform: scale(1.05);
}

/* Descripción debajo de la card */
.descripcion {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.5s ease, padding 0.3s ease;
  background: var(--desc-bg);
  color: var(--desc-text);
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
  padding: 0 20px;
  margin-top: 10px;
  border: 1px solid var(--table-border);
}

.descripcion.abierto {
  max-height: 500px;
  padding: 20px;
}

/* Botón toggle */
.toggle-desc {
  background: var(--primary-color);
  color: white;
  border: none;
  padding: 8px 20px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  margin-bottom: 10px;
  transition: all 0.2s;
}

.toggle-desc:hover {
  background: var(--primary-hover);
  transform: scale(1.05);
}

/* Tabla de perfumes comparados */
.table_perfumes {
  width: 90%;
  max-width: 1000px;
  margin: 20px 0;
  overflow-x: auto;
}

table {
  border-collapse: collapse;
  width: 100%;
  text-align: center;
  background: var(--table-bg);
  color: var(--card-text);
  border: 1px solid var(--table-border);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
}

th {
  background: var(--table-header-bg);
  color: var(--table-header-text);
  font-size: 1rem;
  padding: 10px;
  border-bottom: 2px solid rgba(255, 255, 255, 0.2);
}

td {
  background: var(--table-bg);
  border: 1px solid var(--table-border);
  padding: 5px;
  max-width: 120px;
  vertical-align: middle;
}

td img {
  width: 80%;
  height: 80px;
  object-fit: contain;
  border-radius: 8px;
}

.carrusel-container {
  width: 95%;
  max-width: 1200px;
  margin: 20px auto;
}

.perfume-card {
  background: var(--card-bg);
  border-radius: 15px;
  padding: 15px;
  text-align: center;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
  border: 1px solid var(--table-border);
}

.perfume-img {
  width: 100%;
  height: 220px;
  object-fit: contain;
}

.logo-tienda {
  height: 40px;
  margin: 10px auto;
  display: block;
}

/* Responsive */
@media (max-width: 768px) {
  .card {
    flex-direction: column;
    align-items: center;
  }

  .imagen_card {
    width: 80%;
    height: 250px;
  }

  .info {
    text-align: center;
    width: 100%;
  }

  .descripcion,
  .table_perfumes {
    width: 95%;
  }

  td img {
    height: 60px;
  }
}
</style>
