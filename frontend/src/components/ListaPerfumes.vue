<template>
    <div class="lista-libros">
        <h1>Perfumes scrapeados</h1>

        <ul v-if="perfumerias.length">
            <li v-for="perfume in perfumerias" :key="perfume.id">
                <img :src="perfume.url_image" alt="Imagen perfume" />
                <p class="title">{{ perfume.nombre }}</p>
                <p class="price">€{{ perfume.precio }}</p>
                <p>Lista</p>
                <button @click="VisualizarPerfume(perfume.id)">Detalles</button>
            </li>
        </ul>

        <p v-else>Cargando perfumes…</p>

        <ul v-if="perfumesClub.length">
            <li v-for="perfume in perfumesClub" :key="perfume.id" style="background-color: aquamarine;">
                <img :src="perfume.url_image" alt="Imagen perfume" />
                <p class="title">{{ perfume.nombre }}</p>
                <p class="price">€{{ perfume.precio }}</p>
                <p>Lista</p>
                <button @click="VisualizarPerfume(perfume.id)">Detalles</button>
            </li>
        </ul>

        <p v-else>Cargando perfumes…</p>
    </div>
</template>

<script>
import { getAllPerfumerias } from "../services/api";
import { getAllPerfumesClub } from "../services/api";

export default {
    name: "ListaPerfumes",
    data() {
        return {
            perfumerias: [],
            perfumesClub: [],
        };
    },
    methods: {
        VisualizarPerfume(id) {
            this.$router.push({ name: "detalle-perfume", params: { id } });
        },
    },
    async mounted() {
        try {
            this.perfumerias = await getAllPerfumerias();
        } catch (error) {
            console.error(error);
        }
        try {
            this.perfumesClub = await getAllPerfumesClub();
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
    object-fit:contain;
    background-color: #fff;
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
