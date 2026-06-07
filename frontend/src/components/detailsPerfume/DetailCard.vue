<template>


 <div class="detail-card-container">
  <div class="card">
    <div class="imagen_card">
      <img :src="imagenCard" alt="Imagen perfume" />
    </div>

    <div class="info">
      <div class="div_logo ">
        <img :src="perfume.store_logo" alt="Logo tienda" />
      </div>

      <h2>{{ perfume.marca }}</h2>
      <p>{{ perfume.nombre }}</p>
      <p>{{ perfume.concentracion }}</p>

      <div class="tamaños-container">
        <p class="tamaños-titulo">Elige tamaño:</p>

        <div class="tamaños-grid">
          <label v-for="precioContenido in perfume.precio_contenido" :key="precioContenido.id_contenido"
            @click="$emit('cambiar-contenido', Number(precioContenido.id_contenido))" :class="{
              seleccionado:
                Number(contenidoSeleccionadoId) === Number(precioContenido.id_contenido)}">

            <input type="radio" :checked="Number(contenidoSeleccionadoId) === Number(precioContenido.id_contenido)
              " :id="'talla-' + precioContenido.id_contenido" />

            <div class="tamaño-info">
              <span class="tamaño-nombre">{{ precioContenido.contenido }}</span>
              <span class="tamaño-precio">{{ precioContenido.precio }} €</span>
            </div>

            <button class="favorito-btn" type="button" @click.stop="asignarPerfume(precioContenido.id_contenido)">
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
  </div>
</template>

<script>
export default {
  name: 'DetailCard',
  props: {
    perfume: Object,
    contenidoSeleccionadoId: Number
  },
  data() {
    return {
      mostrarDescripcion: false
    }
  },

  computed: {
    imagenCard() {
      if (!this.perfume || !this.contenidoSeleccionadoId) {
        return "";
      }

      const precioSeleccionado =
        this.perfume.precio_contenido.find(
          p =>
            Number(p.id_contenido) ===
            Number(this.contenidoSeleccionadoId)
        );

      return (
        precioSeleccionado?.image_url_precio_contenido ||
        ""
      );
    }
  },

  methods: {
    abrirNuevaPestana(url) {
      window.open(url, "_blank");
    },

    asignarPerfume(idContenido) {
      this.$emit("id-perfume", idContenido);
    },
  },

  mounted() {

  }
}
</script>

<style scoped>
.detail-card-container{
   display: flex;
   flex-direction: column;
  align-items: center;
  width: 100%;
}
.card {
  display: flex;
  flex-direction: row;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%);
  backdrop-filter: blur(20px);
  border-radius: 30px;
  box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4),
    inset 0 1px 1px rgba(255, 255, 255, 0.1);
  margin: 20px 0 30px;
  width: 80%;
  max-width: 1200px;
  gap: 40px;
  align-items: flex-start;

  border: 1px solid rgba(212, 175, 55, 0.2);
  position: relative;
  z-index: 1;
  overflow: hidden;
}


.card:hover::before {
  opacity: 1;
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


.info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 15px;
  padding: 30px 30px 30px 0;
  color: var(--semiyelow-text);

}

.div_logo {
  width: 100%;
  height: 40px;
  margin-top: 5px;
  margin-bottom: 10px;
  background: var(--store-card-bg);
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
}

.div_logo img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}


.info h2 {
  margin: 0 0 5px;
  font-size: 2.2rem;
  color: var(--semiyelow-text);
  border-left: none;
  padding-left: 0;
  font-family: 'Cormorant Garamond', serif;
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
  background: var(--btn-bg);
  color:var(--btn-text);
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


button:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 30px rgba(212, 175, 55, 0.4);
 
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
@media (max-width: 768px) {
  
.card {
   
 flex-direction: column;
  gap: 50px;
  align-items: center;
}
.info{
  width: 100%;
  align-items: center;
  padding: 10px;
  text-align: center;
}



}

@media (max-width: 480px) {
 
}
</style>