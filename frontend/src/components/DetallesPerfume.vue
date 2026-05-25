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

         <div class="tamaños-container">
  <p class="tamaños-titulo">Elige tamaño:</p>
  
  <div class="tamaños-grid">
    <label v-for="precioContenido in perfume.precio_contenido" 
           :key="precioContenido.id_contenido"
           :class="{ 'seleccionado': contenidoSeleccionadoId === Number(precioContenido.id_contenido) }">
      
      <input type="radio" v-model="contenidoSeleccionadoId" 
             :value="Number(precioContenido.id_contenido)"
             :id="'talla-' + precioContenido.id_contenido" />
      
      <div class="tamaño-info">
        <span class="tamaño-nombre">{{ precioContenido.contenido }}</span>
        <span class="tamaño-precio">{{ precioContenido.precio }} €</span>
      </div>
      
      <button class="favorito-btn" @click.stop="asignarPerfume(precioContenido.id_contenido)">
        🤍
      </button>
    </label>
  </div>
</div>

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
          <thead>
            <tr>
              <th>Imagen</th>
              <th>Marca</th>
              <th>Nombre</th>
              <th>Concentración</th>
              <th>Formato</th> <!-- ← NUEVA COLUMNA -->
              <th>Precio</th>
              <th>Tienda</th>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in perfumesComparados" :key="item.id_contenido"
              :class="{ 'mejor-precio': item.esMejorPrecio }">
              <td>
                <img :src="item.imagen" alt="Imagen perfume" />
              </td>
              <td>{{ item.marca }}</td>
              <td>{{ item.nombre }}</td>
              <td>{{ item.concentracion }}</td>
              <td>{{ item.formato }}</td> <!-- ← NUEVO: muestra el formato -->
              <td>{{ item.precio }} €</td>
              <td>
                <img :src="item.store_logo" alt="Logo tienda" style="height: 30px" />
              </td>
              <td>
                <button @click="cargarPerfume(item.id)">
                  Detalles
                </button>
              </td>
              <td>
                <button @click="abrirNuevaPestana(item.perfume_url)">
                  Ir a tienda
                </button>
              </td>
            </tr>
          </tbody>
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

      const filasComparacion = [];

      this.perfumes.forEach(p => {
        if (p.id === this.perfume.id) return;
        if (p.nombre.toLowerCase() !== this.perfume.nombre.toLowerCase()) return;

        if (p.precio_contenido && p.precio_contenido.length) {
          p.precio_contenido.forEach(contenido => {
            filasComparacion.push({
              id: p.id,
              id_contenido: contenido.id_contenido,
              marca: p.marca,
              nombre: p.nombre,
              concentracion: p.concentracion,
              formato: contenido.contenido,  // "EDT 100ml"
              precio: contenido.precio,
              imagen: contenido.image_url_precio_contenido,
              store_logo: p.store_logo,
              perfume_url: p.perfume_url,
              web: p.store_name || p.fuente  // nombre de la web
            });
          });
        }
      });

      // Calcular el mejor precio por cada formato
      const mejorPrecioPorFormato = {};
      filasComparacion.forEach(item => {
        const key = item.formato;
        if (!mejorPrecioPorFormato[key] || item.precio < mejorPrecioPorFormato[key]) {
          mejorPrecioPorFormato[key] = item.precio;
        }
      });

      // Añadir flag "mejor precio" a cada fila
      this.perfumesComparados = filasComparacion.map(item => ({
        ...item,
        esMejorPrecio: item.precio === mejorPrecioPorFormato[item.formato]
      })).sort((a, b) => a.precio - b.precio);
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
   DISEÑO PREMIUM - PERFUME LUXURY
================================ */

/* Fuentes premium */
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap');
body {
    background: var(--bg-color);
    color: var(--text-color);
    margin: 0;
}
.detalles-libro {
  padding: 40px 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  
  min-height: 100vh;
  position: relative;
}

/* Efecto de partículas de fondo */
.detalles-libro::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: radial-gradient(circle at 25% 50%, rgba(255, 215, 0, 0.05) 0%, transparent 50%),
                    radial-gradient(circle at 75% 80%, rgba(233, 30, 99, 0.08) 0%, transparent 60%);
  pointer-events: none;
  z-index: 0;
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

h2 {
  font-family: 'Cormorant Garamond', serif;
  font-size: 1.8rem;
  margin: 30px 0 20px;
   color: var(--semiyelow-text);
  font-weight: 600;
  letter-spacing: 1px;
  position: relative;
  z-index: 1;
  display: inline-block;
}

h2::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 0;
  width: 60px;
  height: 2px;
  background: linear-gradient(90deg, #d4af37, transparent);
}

/* ================================
   TARJETA PRINCIPAL (CRYSTAL CARD)
================================ */
.card {
  display: flex;
  flex-direction: row;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%);
  backdrop-filter: blur(20px);
  border-radius: 30px;
  box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4),
              inset 0 1px 1px rgba(255, 255, 255, 0.1);
  margin: 20px 0 30px;
  width: 90%;
  max-width: 1200px;
  gap: 40px;
  align-items: flex-start;
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  border: 1px solid rgba(212, 175, 55, 0.2);
  position: relative;
  z-index: 1;
  overflow: hidden;
}

/* Efecto de brillo en borde */
.card::before {
  content: '';
  position: absolute;
  top: -2px;
  left: -2px;
  right: -2px;
  bottom: -2px;
 
  border-radius: 30px;
  opacity: 0;
  transition: opacity 0.5s ease;
  z-index: -1;
}

.card:hover::before {
  opacity: 1;
}

.card:hover {
  transform: translateY(-10px);
  box-shadow: 0 40px 80px rgba(0, 0, 0, 0.5);
}

/* Imagen del perfume con marco dorado */
.imagen_card {
  flex: 0 0 40%;
  background: radial-gradient(ellipse at 30% 40%, rgba(212, 175, 55, 0.1), rgba(0, 0, 0, 0.6));
  border-radius: 25px;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 30px;
  margin: 20px;
  position: relative;
  border: 1px solid rgba(212, 175, 55, 0.3);
  box-shadow: inset 0 0 30px rgba(0, 0, 0, 0.3);
}

/* Efecto de brillo sutil en la imagen */
.imagen_card::before {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: linear-gradient(
    45deg,
    transparent 40%,
    rgba(212, 175, 55, 0.08) 50%,
    transparent 60%
  );
  animation: shimmer 12s infinite linear;
  pointer-events: none;
  z-index: 1;
}

@keyframes shimmer {
  0% {
    transform: translateX(-100%) translateY(-100%) rotate(45deg);
  }
  100% {
    transform: translateX(100%) translateY(100%) rotate(45deg);
  }
}

.imagen_card::after {
  content: '';
  position: absolute;
  top: 10px;
  left: 10px;
  right: 10px;
  bottom: 10px;
  border: 1px solid rgba(212, 175, 55, 0.2);
  border-radius: 20px;
  pointer-events: none;
  z-index: 1;
}

.imagen_card img {
  width: 100%;
  height: auto;
  max-height: 350px;
  object-fit: contain;
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  z-index: 2;
  background: transparent;
  filter: brightness(1.02) contrast(1.05) drop-shadow(0 15px 25px rgba(0, 0, 0, 0.4));
}

.imagen_card img:hover {
  transform: scale(1.05);
  filter: brightness(1.05) drop-shadow(0 20px 35px rgba(212, 175, 55, 0.2));
}

/* Info a la derecha - estilo lujo */
.info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 15px;
  padding: 30px 30px 30px 0;
  color: var(--semiyelow-text);
}

.info img {
  height: 50px;
  object-fit: contain;
  filter: brightness(0) invert(1);
  opacity: 0.9;
}

.info h2 {
  margin: 0 0 5px;
  font-size: 2.2rem;
   color: var(--semiyelow-text);
  border-left: none;
  padding-left: 0;
  font-family: 'Cormorant Garamond', serif;
}

.info h2::after {
  background: linear-gradient(90deg, #d4af37, transparent);
  width: 80px;
}

.info p {
  margin: 0;
  font-size: 1rem;
  color: var(--semiyelow-text);
  font-family: 'Montserrat', sans-serif;
}

.info p:first-of-type {
  font-size: 1.3rem;
 color: var(--semiyelow-text);
  font-weight: 500;
  letter-spacing: 1px;
}

.info p:first-of-type::before {
  content: '❧ ';
  color: #d4af37;
}

/* ================================
   TAMAÑOS GRID (DISEÑO PREMIUM)
================================ */
.tamaños-container {
  margin: 15px 0;
  width: 100%;
}

.tamaños-titulo {
  color: var(--semiyelow-text);
  font-size: 0.8rem;
  letter-spacing: 1px;
  margin-bottom: 12px;
  text-transform: uppercase;
  font-family: 'Montserrat', sans-serif;
}

.tamaños-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  gap: 10px;
}

.tamaños-grid label {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
  padding: 10px 12px;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 1px solid rgba(212, 175, 55, 0.2);
  margin: 0;
  gap: 8px;
}

.tamaños-grid label:hover {
  background: rgba(212, 175, 55, 0.12);
  border-color: #d4af37;
  transform: translateY(-2px);
}

.tamaños-grid label.seleccionado {
  background: rgba(212, 175, 55, 0.2);
  border-color: #d4af37;
  box-shadow: 0 0 10px rgba(212, 175, 55, 0.2);
}

.tamaños-grid input[type="radio"] {
  display: none;
}

.tamaño-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.tamaño-nombre {
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--semiyelow-text);
  font-family: 'Montserrat', sans-serif;
}

.tamaño-precio {
  font-size: 0.75rem;
  color: #d4af37;
  margin-top: 2px;
  font-weight: 500;
}

.favorito-btn {
  background: rgba(255, 255, 255, 0.08);
  padding: 6px 10px;
  font-size: 0.75rem;
  margin: 0;
  border-radius: 20px;
  box-shadow: none;
  min-width: 36px;
}

.favorito-btn:hover {
  background: rgba(233, 30, 99, 0.3);
  transform: scale(1.05);
}

/* ================================
   DESCRIPCIÓN
================================ */
.toggle-desc {
  background: linear-gradient(135deg, #2a2a3e, #1a1a2e);
  color: #d4af37;
  border: 1px solid rgba(212, 175, 55, 0.3);
  margin: 20px 0 0;
  position: relative;
  z-index: 1;
}

.descripcion {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.6s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02));
  backdrop-filter: blur(20px);
  border-radius: 20px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
  padding: 0 30px;
  margin-top: 15px;
  width: 90%;
  max-width: 1200px;
  border: 1px solid rgba(212, 175, 55, 0.2);
  position: relative;
  z-index: 1;
}

.descripcion.abierto {
  max-height: 800px;
  padding: 30px;
}

.descripcion p {
  color: var(--semiyelow-text);
  line-height: 1.8;
  text-align: justify;
  font-family: 'Montserrat', sans-serif;
  font-size: 1rem;
}

/* ================================
   TABLA DE COMPARACIÓN (LUXURY)
================================ */
.table_perfumes {
  width: 95%;
  max-width: 1300px;
  margin: 20px 0;
  overflow-x: auto;
  position: relative;
  z-index: 1;
}

table {
  border-collapse: separate;
  border-spacing: 0;
  width: 100%;
  text-align: center;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02));
  backdrop-filter: blur(20px);
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
  border: 1px solid rgba(212, 175, 55, 0.2);
}

th {
  background: linear-gradient(135deg, #0a0a0a, #1a1a2e);
  color: #d4af37;
  font-size: 0.85rem;
  font-weight: 600;
  padding: 18px 12px;
  text-transform: uppercase;
  letter-spacing: 2px;
  font-family: 'Montserrat', sans-serif;
  border-bottom: 1px solid rgba(212, 175, 55, 0.3);
}

td {
  background: transparent;
  border-bottom: 1px solid rgba(212, 175, 55, 0.1);
  padding: 15px 10px;
  vertical-align: middle;
  transition: all 0.3s ease;
  color: var(--semiyelow-text);
  font-family: 'Montserrat', sans-serif;
}

tr:hover td {
  background: rgba(212, 175, 55, 0.08);
  color: var(--semiyelow-text);
}

td img {
  width: 70px;
  height: 70px;
  object-fit: contain;
  border-radius: 12px;
  background: rgba(0, 0, 0, 0.3);
  padding: 8px;
  transition: all 0.3s ease;
}

td img:hover {
  transform: scale(1.05);
}

td img[alt="Logo tienda"] {
  width: 45px;
  height: 45px;
  background: none;
  filter: brightness(0) invert(1);
  opacity: 0.7;
}

tr:hover td img[alt="Logo tienda"] {
  opacity: 1;
}

/* Mejor precio - destacado */
.mejor-precio td {
  background: linear-gradient(90deg, rgba(212, 175, 55, 0.15), rgba(212, 175, 55, 0.05));
  position: relative;
}

.mejor-precio td:first-child {
  border-left: 4px solid #d4af37;
}

.mejor-precio td:first-child::before {
  content: '✦';
  position: absolute;
  left: -15px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 18px;
  color: #d4af37;
}

.mejor-precio td:nth-child(6) {
  color: #d4af37;
  font-weight: bold;
  font-size: 1.1rem;
}

/* Botones dentro de tabla */
td button {
  padding: 8px 16px;
  font-size: 0.8rem;
  margin: 0 4px;
}

/* Botones premium generales */
button {
  margin: 5px 8px 5px 0;
  background: linear-gradient(135deg, #d4af37, #b8942e);
  color: #0a0a0a;
  border: none;
  padding: 12px 24px;
  border-radius: 50px;
  cursor: pointer;
  font-weight: 600;
  font-size: 0.9rem;
  font-family: 'Montserrat', sans-serif;
  letter-spacing: 1px;
  transition: all 0.3s ease;
  box-shadow: 0 5px 20px rgba(212, 175, 55, 0.2);
  position: relative;
  overflow: hidden;
}

button::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s ease;
}

button:hover::before {
  left: 100%;
}

button:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 30px rgba(212, 175, 55, 0.4);
  background: linear-gradient(135deg, #e6c856, #c4a22a);
}

button:active {
  transform: translateY(1px);
}

/* Botones secundarios */
.info button:not(.favorito-btn),
td button:first-of-type {
  background: rgba(255, 255, 255, 0.08);
  backdrop-filter: blur(10px);
  color: var(--semiyelow-text);
  border: 1px solid rgba(212, 175, 55, 0.3);
  box-shadow: none;
}

.info button:not(.favorito-btn):hover,
td button:first-of-type:hover {
  background: rgba(212, 175, 55, 0.2);
  border-color: #d4af37;
  color: #d4af37;
}

/* ================================
   CARRUSEL PREMIUM
================================ */
.carrusel-container {
  width: 95%;
  max-width: 1300px;
  margin: 30px auto;
  position: relative;
  z-index: 1;
}

.perfume-card {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02));
  backdrop-filter: blur(10px);
  border-radius: 20px;
  padding: 25px 20px;
  text-align: center;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  height: 100%;
  display: flex;
  flex-direction: column;
  border: 1px solid rgba(212, 175, 55, 0.2);
  position: relative;
  overflow: hidden;
}

.perfume-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 3px;
  background: linear-gradient(90deg, #d4af37, #e91e63, #d4af37);
  transition: left 0.5s ease;
}

.perfume-card:hover::before {
  left: 0;
}

.perfume-card:hover {
  transform: translateY(-12px);
  box-shadow: 0 30px 50px rgba(0, 0, 0, 0.4);
  border-color: rgba(212, 175, 55, 0.5);
}

.perfume-img {
  width: 100%;
  height: 220px;
  object-fit: contain;
  margin-bottom: 20px;
  transition: transform 0.4s ease;
  filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.2));
}

.perfume-card:hover .perfume-img {
  transform: scale(1.05);
}

.logo-tienda {
  height: 35px;
  margin: 15px auto;
  display: block;
  object-fit: contain;
  filter: brightness(0) invert(1);
  opacity: 0.6;
  transition: opacity 0.3s ease;
}

.perfume-card:hover .logo-tienda {
  opacity: 1;
}

.perfume-card h3 {
  font-size: 1rem;
  margin: 15px 0 8px;
 color: var(--semiyelow-text);
  font-weight: 600;
  font-family: 'Montserrat', sans-serif;
}

.perfume-card p {
  font-size: 0.85rem;
  color: var(--semiyelow-text);
  margin-bottom: 20px;
  font-family: 'Montserrat', sans-serif;
}

.perfume-card button {
  background: linear-gradient(135deg, #d4af37, #b8942e);
  padding: 10px 20px;
  font-size: 0.85rem;
  margin-top: auto;
}

/* ================================
   RESPONSIVE PREMIUM
================================ */
@media (max-width: 968px) {
  .card {
    flex-direction: column;
    align-items: center;
    gap: 20px;
    padding: 30px;
  }

  .imagen_card {
    width: 70%;
    margin: 0 auto;
  }

  .info {
    padding: 0;
    text-align: center;
    width: 100%;
  }

  .tamaños-grid {
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
  }

  h1 {
    font-size: 2rem;
  }

  h1::before,
  h1::after {
    display: none;
  }

  h2 {
    font-size: 1.5rem;
  }
}

@media (max-width: 768px) {
  .detalles-libro {
    padding: 20px 15px;
  }

  h1 {
    font-size: 1.6rem;
  }

  .imagen_card {
    width: 85%;
  }

  .info h2 {
    font-size: 1.6rem;
  }

  th {
    font-size: 0.7rem;
    padding: 12px 6px;
  }

  td {
    font-size: 0.75rem;
    padding: 10px 5px;
  }

  td img {
    width: 45px;
    height: 45px;
  }

  .table_perfumes {
    width: 100%;
  }

  .mejor-precio td:first-child::before {
    display: none;
  }

  .tamaños-grid {
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 8px;
  }

  .tamaños-grid label {
    padding: 8px 10px;
  }

  .tamaño-nombre {
    font-size: 0.75rem;
  }

  .tamaño-precio {
    font-size: 0.7rem;
  }
}

@media (max-width: 480px) {
  .card {
    padding: 20px;
  }

  .imagen_card {
    width: 95%;
    padding: 15px;
  }

  .tamaños-grid {
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
  }

  button {
    width: 100%;
    margin: 5px 0;
  }

  .info button {
    width: auto;
    margin: 5px 5px 5px 0;
  }
}

/* ================================
   ANIMACIONES PREMIUM
================================ */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes glowPulse {
  0%, 100% {
    box-shadow: 0 0 5px rgba(212, 175, 55, 0.2);
  }
  50% {
    box-shadow: 0 0 20px rgba(212, 175, 55, 0.4);
  }
}

.card, .table_perfumes, .carrusel-container {
  animation: fadeInUp 0.6s ease-out;
}

.card {
  animation-delay: 0.1s;
}

.table_perfumes {
  animation-delay: 0.2s;
}

.carrusel-container {
  animation-delay: 0.3s;
}

/* Scrollbar premium */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #1a1a2e;
  border-radius: 10px;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #d4af37, #b8942e);
  border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(135deg, #e6c856, #c4a22a);
}
</style>