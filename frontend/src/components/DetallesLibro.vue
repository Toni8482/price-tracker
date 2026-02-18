<template>
  <div class="detalles-libro">
    <h1>Detalles de libro</h1>

    <div v-if="book" class="card">
      <img :src="book.image" alt="Portada del libro" />
      <div class="info">
        <p><strong>ID:</strong> {{ book.id }}</p>
        <p><strong>Título:</strong> {{ book.title }}</p>
        <p><strong>Precio:</strong> €{{ book.price }}</p>
        <button @click="$router.back()">Volver a la lista</button>
      </div>
    </div>

    <div v-else>
      <p>Cargando...</p>
    </div>
  </div>
</template>

<script>
import { getBook } from "../services/api";

export default {
  name: "DetallesLibro",
  data() {
    return {
      book: null,
    };
  },
  async mounted() {
    const id = this.$route.params.id;
    try {
      this.book = await getBook(id);
    } catch (error) {
      console.error(error);
    }
  },
};
</script>

<style scoped>
.detalles-libro {
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

h1 {
  color: #4b0082;
  margin-bottom: 30px;
}

/* Tarjeta de detalle */
.card {
  display: flex;
  flex-direction: column;
  align-items: center;
  background: #a45ed8;
  padding: 20px;
  border-radius: 15px;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
  max-width: 400px;
  width: 100%;
}

/* Imagen */
img {
  width: 100%;
  border-radius: 10px;
  margin-bottom: 20px;
  border: 2px solid #fff;
}

/* Info */
.info p {
  color: #fff;
  margin: 5px 0;
  font-size: 1rem;
}

/* Botón volver */
button {
  margin-top: 15px;
  background-color: #4b0082;
  color: white;
  border: none;
  padding: 8px 20px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
  transition: background 0.3s, transform 0.2s;
}

button:hover {
  background-color: #6a1aa6;
  transform: scale(1.05);
}
</style>
