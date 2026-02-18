<template>
  <div class="lista-libros">
    <h1>Libros scrapeados</h1>

    <ul v-if="books.length">
      <li v-for="(book, index) in books" :key="index">
        <img :src="book.image" alt="Portada del libro" />
        <p class="title">{{ book.title }}</p>
        <p class="price">€{{ book.price }}</p>
        <button @click="VisualizarLibro(book.id)">Detalles</button>
      </li>
    </ul>

    <p v-else>Cargando libros…</p>
  </div>
</template>

<script>
import { getBooks } from "../services/api";

export default {
  name: "ListaLibros",
  data() {
    return {
      books: [],
    };
  },
  methods: {
    VisualizarLibro(id) {
      this.$router.push({ name: "detalle-libro", params: { id } });
    },
  },
  async mounted() {
    try {
      this.books = await getBooks();
    } catch (error) {
      console.error(error);
    }
  },
};
</script>

<style scoped>
.lista-libros {
  padding: 20px;
}

h1 {
  text-align: center;
  color: #4b0082;
  margin-bottom: 30px;
  font-family: 'Segoe UI', sans-serif;
}

/* Grid de libros */
ul {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 25px;
  padding: 0;
  margin: 0;
  list-style: none;
}

/* Cada tarjeta de libro */
li {
  background: #a45ed8;
  border-radius: 12px;
  padding: 15px;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s, background 0.3s;
  display: flex;
  flex-direction: column;
  align-items: center;
}

li:hover {
  background: #caa0f1;
  transform: translateY(-5px);
}

/* Imagen */
img {
  width: 100%;
  max-height: 200px;
  object-fit: cover;
  border-radius: 8px;
  margin-bottom: 10px;
  border: 2px solid #fff;
}

/* Título y precio */
.title {
  font-weight: bold;
  margin: 5px 0;
  color: #fff;
}

.price {
  color: #f0e68c;
  margin-bottom: 10px;
}

/* Botón */
button {
  background-color: #4b0082;
  color: white;
  border: none;
  padding: 8px 15px;
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
