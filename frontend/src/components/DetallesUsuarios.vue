<template>
  <div class="detalles-libro">
    <h1>Detalles de usuario</h1>
    <div v-if="usuario">
      <div class="card">
       

        <div class="info">
         


          <h2>{{ usuario.email }}</h2>
          <p> {{usuario.password}}</p>
          <p> {{usuario.id}}</p>


         


        </div>

      </div>
    
    </div>
    <div v-else>
      <p>Cargando...</p>
    </div>
   
  </div>



</template>

<script>
import {getUser } from "../services/api";

export default {
  name: "DetallesUsuario",

  data() {
    return {
      usuario: null,
   
    };
  },

  watch: {
  
    "$route.params.id": {
      immediate: true,
      handler(newId) {
        this.cargarUsuario(newId);

      },
    },
   
  },
  computed: {
   
  },
  methods: {

   async cargarUsuario(id){
        this.usuario = await getUser(id);
          
    }
  }
};
</script>

<style scoped>
/* Contenedor principal */
.detalles-libro {
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

h1 {
  color: #4b0082;
  margin-bottom: 30px;
  text-align: center;
  font-family: 'Segoe UI', sans-serif;
}

/* Tarjeta principal */
.card {
  display: flex;
  flex-direction: row;
  /* Imagen izquierda, info derecha */
  background: #a45ed8;
  padding: 20px;
  border-radius: 15px;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
  margin: 30px 0 10px;
  /* espacio abajo para la descripción */
  width: 90%;
  max-width: 900px;
  gap: 20px;
  align-items: flex-start;
}

/* Imagen del perfume a la izquierda */
.imagen_card {
  flex: 0 0 40%;
  /* ocupa 40% del ancho */
  height: 300px;
  border-radius: 12px;
  overflow: hidden;
  border: 2px solid #fff;
  display: flex;
  align-items: center;
  justify-content: center;
}

.imagen_card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
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
  color: #fff;
}

/* Info items */
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
  background: #fff;
  color: #4b0082;
  padding: 6px 12px;
  border-radius: 20px;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.2s ease;
}

.size-options label:hover {
  background: #f0e6ff;
}

.size-options input[type="radio"] {
  appearance: none;
  -webkit-appearance: none;
  width: 18px;
  height: 18px;
  border: 2px solid #4b0082;
  border-radius: 50%;
  cursor: pointer;
  position: relative;
}

.size-options input[type="radio"]:checked::before {
  content: '';
  display: block;
  width: 10px;
  height: 10px;
  background: #4b0082;
  border-radius: 50%;
  margin: 2px;
}

/* Botones */
button {
  margin: 5px 0;
  background-color: #4b0082;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s, transform 0.2s;
}

button:hover {
  background-color: #6a1aa6;
  transform: scale(1.05);
}

/* Descripción debajo de la card */
/* Contenedor colapsado inicialmente */
.descripcion {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.5s ease, padding 0.3s ease;
  background: #f8f0ff;
  color: #201b1b;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  padding: 0 20px;
  margin-top: 10px;
}

/* Cuando está abierto */
.descripcion.abierto {
  max-height: 500px;
  /* suficiente para tu contenido */
  padding: 20px;
}

/* Botón toggle */
.toggle-desc {
  background-color: #4b0082;
  color: white;
  border: none;
  padding: 8px 20px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  margin-bottom: 10px;
  transition: background 0.3s, transform 0.2s;
}

.toggle-desc:hover {
  background-color: #6a1aa6;
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
}

th {
  background-color: #f5deb3;
  font-size: 1rem;
  padding: 10px;
}

td {
  background-color: rgb(214, 213, 211);
  border: solid 2px #fff;
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
