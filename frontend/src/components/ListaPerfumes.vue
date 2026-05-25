<template>
    <div class="div_template">
        <div class="content_title">
            <h1>Perfumes</h1>

        </div>
        <div class="content_lista">
            <div class="filtros-content">
                <div class="seleccion_filtrado">
                    <p>Genero</p>
                    <label>
                        <input type="radio" v-model="generoSeleccionado" name="genero" value="todos">
                        Todos
                    </label>
                    <label>
                        <input type="radio" v-model="generoSeleccionado" name="genero" value="mujeres">
                        Mujeres {{ contadorMujeres }}
                    </label>
                    <label>
                        <input type="radio" v-model="generoSeleccionado" name="genero" value="hombres">
                        Hombres {{ contadorHombres }}
                    </label>
                </div>

                <div class="seleccion_filtrado">
                    <p>Tiendas</p>
                    <label>
                        <input type="radio" v-model="webSiteSeleccionado" name="tienda" value="todos">
                        Todos
                    </label>
                    <label>
                        <input type="radio" v-model="webSiteSeleccionado" name="tienda" value="perfumerias">
                        Perfumerias
                    </label>
                    <label>
                        <input type="radio" v-model="webSiteSeleccionado" name="tienda" value="perfumesClub">
                        PerfumesClub
                    </label>
                </div>
            </div>






            <div class="lista-libros">
                <ul v-if="this.perfumes.length">
                    <li v-for="perfume in filteredPerfumes" :key="perfume.id">

                        <div class="div_imagen">
                            <img :src="obtenerPrecioSeleccionado(perfume)?.image_url_precio_contenido"
                                alt="Imagen perfume" />
                        </div>

                        <p class="marca">{{ perfume.marca }}</p>
                        <p class="title">{{ perfume.nombre }}</p>
                        <p class="price">{{ obtenerPrecioSeleccionado(perfume)?.precio }} €</p>
                        <p class="">{{ perfume.target_public }}</p>

                        <div class="div_logo">
                            <img :src="perfume.store_logo" alt="Imagen logo" />
                        </div>

                        <div>
                            <select v-model="perfume.precioSeleccionadoId">
                                <option v-for="precioContenido in perfume.precio_contenido"
                                    :key="precioContenido.id_contenido" :value="precioContenido.id_contenido">
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
    </div>

</template>

<script>
import { getAllPerfumes } from "../services/api";

export default {
    name: "ListaPerfumes",
    props: {

    },
    data() {
        return {
            perfumes: [],
            selectedStore: "",
            selectedPrice: "",
            selectedName: "",
            selectedContenido: "",
            mostrarFiltros: false, // controla el desplegable
            webSiteSeleccionado: 'todos',
            generoSeleccionado: 'todos',
            contadorMujeres: 0,
            contadorHombres: 0,
        };
    },
    methods: {
        VisualizarPerfume(id) {
            this.$router.push({ name: "detalle-perfume", params: { id } });
        },

        AgruparPerfumeIgual() {
            let result = this.perfumes;

            result = result.slice().sort((a, b) => {
                const precioA = this.obtenerPrecioSeleccionado(a)?.precio || 0;
                const precioB = this.obtenerPrecioSeleccionado(b)?.precio || 0;

                return precioA - precioB;
            });

            result = result.filter(
                (p, indice, arr) =>
                    indice === arr.findIndex(x => x.nombre === p.nombre)
            );

            return result;
        },
        obtenerPrecioSeleccionado(perfume) {
            return perfume.precio_contenido.find(
                p => p.id_contenido === perfume.precioSeleccionadoId
            );
        }
    },
    computed: {
        filteredPerfumes() {
            let result = this.perfumes;


            if (this.generoSeleccionado != '') {
                switch (this.generoSeleccionado) {
                    case 'todos':
                        result = result;
                        break;
                    case 'hombres':
                        result = result.filter(p => p.target_public == 'Hombre');
                        break;
                    case 'mujeres':
                        result = result.filter(p => p.target_public == 'Mujer');
                        break;

                    default:
                        break;
                }
            }



            if (this.webSiteSeleccionado != '') {
                switch (this.webSiteSeleccionado) {
                    case 'todos':
                        result = result;
                        break;
                    case 'perfumerias':
                        result = result.filter(p => p.store_name == 'Perfumerias');
                        break;
                    case 'perfumesClub':
                        result = result.filter(p => p.store_name == 'Perfumes Club');
                        break;

                    default:
                        break;
                }
            }



            if (this.$route.query.nombre) {
                const queryNombre = this.$route.query.nombre
                const q = queryNombre.toLowerCase()
                result = result.filter(p =>
                    p.nombre.toLowerCase().includes(q)
                )
            }

            if (this.selectedName != "") {
                const query = this.selectedName.toLowerCase();
                result = result.filter(p => p.nombre.toLowerCase().startsWith(query));
                result = result.slice().sort((a, b) => a.nombre.localeCompare(b.nombre));
            }

            return result;
        },
    },
    async mounted() {
        try {
            this.perfumes = await getAllPerfumes();
            this.perfumes = this.perfumes.map(p => ({
                ...p,
                precioSeleccionadoId: p.precio_contenido?.[0]?.id_contenido || null,
            }));
            this.perfumes = this.AgruparPerfumeIgual();


            this.contadorHombres = this.perfumes.filter(p => p.target_public == 'Hombre').length;

            this.contadorMujeres = this.perfumes.filter(p => p.target_public == 'Mujer').length;

        } catch (error) {
            console.error(error);
        }
    },

};
</script>

<style scoped>
/* ================================
   BODY Y TITULOS
================================ */
body {
    background: var(--bg-color);
    color: var(--text-color);
    margin: 0;
}

h1 {
    text-align: center;
    color: var(--text-color);
}

/* ================================
   GRID DE PERFUMES
================================ */
ul {
    display: grid;
    grid-template-columns: repeat(5, 200px);
    gap: 25px;
    padding: 0;
    margin: 0;
    list-style: none;
    justify-content: center;
}

/* ================================
   TARJETA
================================ */
li {
    background: var(--card-bg);
    backdrop-filter: blur(10px);
    border-radius: 14px;
    padding: 15px;
    text-align: center;
    border: 1px solid var(--card-border);
    box-shadow: 0 10px 25px var(--card-shadow);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 0;
}

li:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 35px var(--card-shadow);
}

/* ================================
   CONTENEDOR TEMPLATE
================================ */
.div_template {
    display: grid;
    grid-template-columns: 200px 200px 200px;
    gap: 20px;
}

/* ================================
   CONTENEDOR DE IMÁGENES
================================ */
.div_imagen {
    width: 100%;
    height: 160px;
    border-radius: 10px;
    overflow: hidden;
    background: var(--card-bg);
    display: flex;
    align-items: center;
    justify-content: center;
}

.div_imagen img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}

li:hover .div_imagen img {
    transform: scale(1.05);
}

/* ================================
   TEXTO
================================ */
.title {
    font-weight: bold;
    font-size: 1rem;
    color: var(--title-color);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.marca {
    font-weight: bold;
    color: var(--marca-color);
}

.price {
    color: var(--price-color);
    font-size: 1.1rem;
    font-weight: bold;
}

/* ================================
   SELECT
================================ */
select {
    width: 100%;
    padding: 8px;
    border-radius: 8px;
    border: none;
    outline: none;
    font-weight: bold;
    cursor: pointer;
    background: var(--select-bg);
    color: var(--select-color);
}

select:hover {
    background: var(--btn-hover);
}

/* ================================
   BOTONES
================================ */
button {
    margin-top: auto;
    background: var(--btn-bg);
    color: var(--select-color);
    border: none;
    padding: 10px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: bold;
    transition: transform 0.2s, background 0.2s;
}

button:hover {
    background: var(--btn-hover);
    transform: scale(1.05);
}

/* ================================
   FILTROS
================================ */
#filters {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 30px;
    text-align: center;
}

/* ================================
   LOGO TIENDA
================================ */
.div_logo {
    width: 100%;
    height: 40px;
    margin-top: 5px;
    background: var(--card-bg);
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

/* ================================
   TITULO Y LAYOUT
================================ */
.content_title {
    grid-column: 1 / 6;
}

.content_lista {
    display: grid;
    grid-template-columns: 300px 220px 220px;
}

.lista-libros {
    padding: 30px;
    margin: 0 auto;
    grid-column: 2 / 4;
}

/* ================================
   FILTROS LATERALES
================================ */
.filtros-content {
    grid-column: 1 / 1;
    margin: 30px;
}

.seleccion_filtrado {
    background: var(--filter-bg);
    border: 1px solid var(--card-border);
    border-radius: 12px;
    margin: 0px;
    display: flex;
    flex-direction: column;
    padding: 20px;
    font-size: 20px;
    color: var(--text-color);
}

/* ================================
   RESPONSIVE
================================ */
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