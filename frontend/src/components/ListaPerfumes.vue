<template>
    <div class="lista-libros">
        <h1>Perfumes scrapeados</h1>
        <div id="filters">
            <label for="">
                Filtro
            </label>
            <select v-model="selectedStore">
                <option value="">Todos</option>
                <option value="1">
                    perfumerias
                </option>
                <option value="2">
                    perfumerias club
                </option>
            </select>


            <label for="">
                Buscar
            </label>
            <input type="text" v-model="selectedName">

            <label for="">
                Orden
            </label>
            <select v-model="selectedPrice">
                <option value="mayor">Mayor precio </option>
                <option value="menor">Menor precio</option>

            </select>
        </div>
        <div>
            <ul v-if="this.perfumes.length">
                <li v-for="perfume in this.perfumes" :key="perfume.id">
                    <img :src="perfume.imagen_url" alt="Imagen perfume" />
                    <p class="marca">{{ perfume.marca }}</p>
                    <p class="title">{{ perfume.nombre }}</p>
                    <p class="price">{{ perfume.precio }}€</p>
                    <p class="">{{ perfume.stock }}</p>

                    <p class="">{{ perfume.store_name }}</p>
                    <a class="" href={{ perfume.perfume_url }}>{{ perfume.perfume_url }}</a>
                    <img :src="perfume.store_logo" alt="Imagen logo" />
                    <button @click="VisualizarPerfume(perfume.id)">Detalles</button>
                </li>
            </ul>

            <p v-else>Cargando perfumes…</p>

        </div>
    </div>
</template>

<script>
import { getAllPerfumes } from "../services/api";


export default {
    name: "ListaPerfumes",
    data() {
        return {
            perfumes: [],
            selectedStore: "",
            selectedPrice: "",
            selectedName: ""
        };
    },
    methods: {
        VisualizarPerfume(id) {
            this.$router.push({ name: "detalle-perfume", params: { id } });
        },

        AgruparPerfumeIgual() {
            let result = this.perfumes;

            result = result.slice().sort((a, b) => (a.precio - b.precio));

            result = result.filter((p, indice, arr) => indice === arr.findIndex(x => x.nombre === p.nombre));

            return result;
        }



    },
    computed: {
        filteredPerfumes() {
            let result = this.perfumes





            // 🔹 Buscador por palabra
            if (this.selectedName != "") {
                const query = this.selectedName.toLowerCase()
                result = result.filter(p => p.nombre.toLowerCase().startsWith(query))

                result = result.slice().sort((a, b) =>
                    a.nombre.localeCompare(b.nombre)
                )
            }



            return result
        }

    },
    async mounted() {
        try {
            this.perfumes = await getAllPerfumes();
            this.perfumes = this.AgruparPerfumeIgual();

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
    grid-template-rows: repeat(auto-fit, minmax(80px, 1fr));
    gap: 25px;
    padding: 0;
    margin: 0;
    list-style: none;
}

/* Cada tarjeta de libro */
li {
    background: #a45ed8;
    border-radius: 12px;
    padding: 10px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s, background 0.3s;
    display: flex;
    flex-direction: row;
    align-items: center;
}

li:hover {
    background: #caa0f1;
    transform: translateY(-5px);
}

/* Imagen */
img {

    max-height: 100px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 10px;
    border: 2px solid #fff;
    object-fit: contain;

    color: black;
}

/* Título y precio */
.title {
    font-weight: bold;
    margin: 5px 0;
    color: #fff;
}

.marca {
    font-weight: bold;
    margin: 5px 0;
    color: #af3030;
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

#filters {
    display: grid;
    margin: 30px;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    text-align: center;
    gap: 10px;
}
</style>
