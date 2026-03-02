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
                <li v-for="perfume in filteredPerfumes" :key="perfume.id">

                    <div class="div_imagen"> <img :src="perfume.precioSeleccionado.image_url_precio_contenido"
                            alt="Imagen perfume" /></div>

                    <p class="marca">{{ perfume.marca }}</p>
                    <p class="title">{{ perfume.nombre }}</p>
                    <p class="price">{{ perfume.precioSeleccionado.precio }} €</p>



                    <p class="">{{ perfume.target_public }}</p>


                    <div class="div_logo"> <img :src="perfume.store_logo" alt="Imagen logo" /></div>


                    <div>


                        <select v-model="perfume.precioSeleccionado">
                            <option v-for="precioContenido in perfume.precio_contenido" :key="precioContenido.contenido"
                                :value="precioContenido">
                                {{ precioContenido.contenido }}
                            </option>
                        </select>
                    </div>
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
            selectedName: "",
            selectedContenido: "",

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
        },



    },
    async mounted() {
        try {
            this.perfumes = await getAllPerfumes();

            this.perfumes = this.perfumes.map(p => ({
                ...p,
                precioSeleccionado: {
                    precio: p.precio_contenido?.[0]?.precio || 0,
                    image_url_precio_contenido: p.precio_contenido?.[0].image_url_precio_contenido || "",
                }

            }));

            this.perfumes = this.AgruparPerfumeIgual();
        } catch (error) {
            console.error(error);
        }
    },
};
</script>

<style scoped>
/* CONTENEDOR PRINCIPAL */
.lista-libros {
    padding: 30px;
    max-width: 1400px;
    margin: 0 auto;
}

/* GRID DE PERFUMES */
ul {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 25px;
    padding: 0;
    margin: 0;
    list-style: none;
}

/* TARJETA */
li {
    background: #a45ed8;
    border-radius: 14px;
    padding: 15px;
    text-align: center;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
    transition: transform 0.25s ease, background 0.25s ease;
    display: flex;
    flex-direction: column;
    gap: 10px;
    min-width: 0;
    /* evita desbordes de select */
}

li:hover {
    background: #caa0f1;
    transform: translateY(-6px);
}

/* CONTENEDOR DE IMÁGENES */
.div_imagen {
    width: 100%;
    height: 160px;
    border: 2px solid #fff;
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* IMÁGENES */
.div_imagen img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease, opacity 0.3s ease;

}

li:hover .div_imagen img {
    transform: scale(1.05);
}




/* TEXTO */
.title {
    font-weight: bold;
    font-size: 1rem;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.marca {
    font-weight: bold;
    color: #8b1d1d;
}

.price {
    color: #f0e68c;
    font-size: 1.1rem;
    font-weight: bold;
}

/* SELECT */
select {
    width: 100%;
    max-width: 100%;
    padding: 6px;
    border-radius: 6px;
    border: none;
    outline: none;
    font-weight: bold;
    cursor: pointer;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* BOTÓN */
button {
    margin-top: auto;
    background-color: #4b0082;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s, transform 0.2s;
}

button:hover {
    background-color: #6a1aa6;
    transform: scale(1.05);
}

/* FILTROS */
#filters {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
    text-align: center;
}

/* LOGO TIENDA */
.div_logo {
    width: 100%;
    height: 40px;
    margin-top: 5px;
    background-color: black;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
    border-radius: 10px;

}


.div_logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease, opacity 0.3s ease;

}

select {
    background-color: #4b0082;
    color: #fff;
    border: none;
    padding: 10px;
    border-radius: 12px;
    font-weight: bold;
    cursor: pointer;
}

select:hover {
    background-color: #6a1aa6;
}

select:focus {
    outline: none;
    box-shadow: 0 0 5px rgba(75, 0, 130, 0.5);
}
/* RESPONSIVE */
@media (max-width: 768px) {
    ul {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 20px;
    }

    .div_imagen {
        height: 140px;
    }

    .title,
    .marca,
    .price {
        font-size: 0.9rem;
    }

    select {
        font-size: 0.9rem;
    }
}
</style>
