<template>
  <div class="detalles-libro">
    <h1>Detalles de usuario</h1>
    <div v-if="usuario">
      <div class="card">


        <div class="info">



          <h2>{{ usuario.email }}</h2>
         
          <p> {{ usuario.id }}</p>





        </div>

      </div>

    </div>
    <div v-else>
      <p>Cargando...</p>
    </div>

  </div>



</template>

<script>
import { editarUsuario, getUsers } from "../services/api";

export default {
  name: "DetallesUsuario",

  data() {
    return {
      usuario: null,
      token: "",

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

    async cargarUsuario(id) {
      this.token = localStorage.getItem('token');
      let usuarios = await getUsers(this.token);

     this.usuario = usuarios.find(u => u.id == id);

    }
  }
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
  color: var(--primary-color);
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
  padding: 20px;
  border-radius: 15px;
  box-shadow: 0 6px 15px rgba(0,0,0,0.3);
  margin: 30px 0 10px;
  width: 90%;
  max-width: 900px;
  gap: 20px;
  align-items: flex-start;
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}

/* Imagen del perfume a la izquierda */
.imagen_card {
  flex: 0 0 40%;
  height: 300px;
  border-radius: 12px;
  overflow: hidden;
  border: 2px solid var(--table-border);
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
  border: 2px solid var(--primary-color);
  border-radius: 50%;
  cursor: pointer;
  position: relative;
}

.size-options input[type="radio"]:checked::before {
  content: '';
  display: block;
  width: 10px;
  height: 10px;
  background: var(--primary-color);
  border-radius: 50%;
  margin: 2px;
}

/* Botones */
button {
  margin: 5px 0;
  background-color: var(--primary-color);
  color: var(--card-text);
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s, transform 0.2s;
}

button:hover {
  background-color: var(--primary-hover);
  transform: scale(1.05);
}

/* Descripción debajo de la card */
.descripcion {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.5s ease, padding 0.3s ease;
  background: var(--description-bg);
  color: var(--description-text);
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  padding: 0 20px;
  margin-top: 10px;
}

.descripcion.abierto {
  max-height: 500px;
  padding: 20px;
}

/* Botón toggle */
.toggle-desc {
  background-color: var(--primary-color);
  color: var(--card-text);
  border: none;
  padding: 8px 20px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  margin-bottom: 10px;
  transition: background 0.3s, transform 0.2s;
}

.toggle-desc:hover {
  background-color: var(--primary-hover);
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
  color: var(--secondary-text);
  border-radius: 12px;
}

th {
  background-color: var(--table-header-bg);
  color: var(--table-header-text);
  font-size: 1rem;
  padding: 10px;
}

td {
  background-color: var(--table-bg);
  border: solid 2px var(--table-border);
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
