<template>
  <div class="detalles-libro">
    <h1>Detalles de perfume</h1>
    <div v-if="perfume">
      <div class="card">
        <div class="imagen_card">
          <img :src="imagenCard" alt="Imagen perfume" />
        </div>

        <div class="info">
          <div>
            <img :src="perfume.store_logo">
            <button @click="asignarPerfume(perfume.id)">🤍</button>
          </div>


          <h2>{{ perfume.marca }}</h2>
          <p> {{ perfume.nombre }}</p>
          <p> {{ perfume.concentracion }}</p>


          <p>Elige tamaño:</p>
          <label v-for="precioContenido in perfume.precio_contenido" :key="precioContenido.contenido">
            <input type="radio" v-model="cantidadSeleccionada" name="tamano" :value="precioContenido.contenido">
            {{ precioContenido.contenido }}
            {{ precioContenido.precio }}


          </label>


          <button @click="$router.back()">Volver a la lista</button><br>
          <button @click="abrirNuevaPestana(perfume.perfume_url)">Ir a tienda</button>
        </div>

      </div>
      <!-- Botón para mostrar/ocultar descripción -->
      <button @click="mostrarDescripcion = !mostrarDescripcion" class="toggle-desc">
        {{ mostrarDescripcion ? 'Ocultar descripción' : 'Ver descripción' }}
      </button>
      <div class="descripcion" :class="{ abierto: mostrarDescripcion }">
        <p v-html="perfume.descripcion"> </p>
      </div>
    </div>
    <div v-else>
      <p>Cargando...</p>
    </div>
    <div class="table_perfumes">
      <table v-if="perfumesComparados.length">
        <tr>
          <th>Imagen</th>
          <th>Marca</th>
          <th>Nombre</th>
          <th>Concentracion</th>

          <th>Tienda</th>
          <th></th>
          <th></th>
        </tr>
        <tr v-for="perfume in perfumesComparados" :key="perfume.id">
          <td> <img :src="perfume.imagen_url" alt="Imagen perfume" /></td>
          <td>{{ perfume.marca }}</td>
          <td>{{ perfume.nombre }}</td>
          <td>{{ perfume.concentracion }}</td>
          <!--
        <td>{{ perfume.precio_contenido.contenido }}</td>
          <td>{{ perfume.precio_contenido.precio }}
            €
           </td>   -->

          <td><img :src="perfume.store_logo" alt="Imagen logo"></td>

          <td> <button @click="cargarPerfume(perfume.id)">Detalles</button></td>
          <td> <button @click="abrirNuevaPestana(perfume.perfume_url)">Ir a tienda</button></td>

        </tr>

      </table>

      <span v-else>Cargando tabla ...</span>


    </div>
  </div>



</template>

<script>
import { getPerfume, getAllPerfumes, addFavorito } from "../services/api";

export default {
  name: "DetallesPerfume",

  data() {
    return {
      perfume: null,
      mostrarDescripcion: false,
      perfumesComparados: [],
      perfumes: [],
      cantidadSeleccionada: "",
    };
  },

  watch: {
    "$route.params.id": {
      immediate: true,
      handler(newId) {
        this.cargarPerfume(newId);

      },
    },
    perfumes() {
      this.PerfumesIguales();
    }
  },
  computed: {
    imagenCard() {
      let result = "";
      if (this.cantidadSeleccionada !== "") {

        this.perfume.precio_contenido.forEach(p => {

          if (p.contenido == this.cantidadSeleccionada) {

            result = p.image_url_precio_contenido;
          }
        });

      }
      return result;
    }
  },
  methods: {
    PerfumesIguales() {
      if (!this.perfume) return;

      this.perfumesComparados = this.perfumes.filter((p) =>
        p.nombre.includes(this.perfume.nombre)
      );
    },

    async cargarPerfume(id) {
      try {
        this.perfume = await getPerfume(id);

        // Seleccionar automáticamente el primer tamaño
        if (this.perfume.precio_contenido?.length) {
          this.cantidadSeleccionada =
            this.perfume.precio_contenido[0].contenido;
        }


      } catch (error) {
        console.error(error);
      }
    },

    abrirNuevaPestana(urlTienda) {
      window.open(urlTienda, "_blank", "noopener,noreferrer");
    },

    async asignarPerfume(id) {
      const token = localStorage.getItem("token");
      const userId = parseInt(localStorage.getItem("user_id")) ;
      const userEmail = localStorage.getItem("user_email");



      await addFavorito(token, id);
      alert(`Asignado perfume con id: ${id} a favoritos del usuario con ID: ${userId} y con email: ${userEmail}`);
    }
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
  box-shadow: 0 10px 25px rgba(0,0,0,0.4);
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
  box-shadow: 0 10px 25px rgba(0,0,0,0.4);
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
  box-shadow: 0 10px 25px rgba(0,0,0,0.4);
}

th {
  background: var(--table-header-bg);
  color: var(--table-header-text);
  font-size: 1rem;
  padding: 10px;
  border-bottom: 2px solid rgba(255,255,255,0.2);
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
