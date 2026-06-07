<template>
    
        <ul v-if="this.perfumes.length">
            <BaseCard v-for="perfume in perfumes" :key="perfume.id" class="card">


                <img :src="obtenerPrecioSeleccionado(perfume)?.image_url_precio_contenido" alt="Imagen perfume" />


                <p class="marca">{{ perfume.marca }}</p>
                <p class="nombre">{{ perfume.nombre }}</p>
                <p class="precio">{{ obtenerPrecioSeleccionado(perfume)?.precio }} €</p>
                <p class="">{{ perfume.target_public }}</p>

                <div class="div_logo">
                    <img :src="perfume.store_logo" alt="Imagen logo" />
                </div>


                <select v-model="perfume.precioSeleccionadoId">
                    <option v-for="precioContenido in perfume.precio_contenido" :key="precioContenido.id_contenido"
                        :value="precioContenido.id_contenido">
                        {{ precioContenido.contenido }}
                    </option>
                </select>

                <button @click="ViewPerfume(perfume.id, perfume.precioSeleccionadoId)">Detalles</button>
            </BaseCard>
        </ul>

        <p v-else>Cargando perfumes…</p>
  
</template>

<script>
import BaseCard from '@/components/base/BaseCard.vue';


export default {
    name: 'ListCards',
    components: {
        BaseCard
    },
    props: {
        perfumes: Array
    },
    data() {
        return {

        }
    },

    computed: {

    },

    methods: {
        ViewPerfume(id, idVariant) {
            this.$router.push({
                name: "detalle-perfume-variante", params: {
                    id: id,
                    idVariante: idVariant
                }
            });
        },

        obtenerPrecioSeleccionado(perfume) {
            return perfume.precio_contenido.find(
                p => p.id_contenido === perfume.precioSeleccionadoId
            );
        }
    },

    mounted() {

    }
}
</script>

<style scoped>




.card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 35px var(--card-shadow);
}

.card img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 10px;
}

.card .marca {
    color: var(--card-marca);
    font-weight: bold;
}

.card .nombre {
    font-weight: bold;
    color: var(--card-title);
    margin: 5px 0;
}

.card .precio {
    color: var(--card-price);
    font-weight: bold;
}

.card .marca,
.card .nombre {
    width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
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

ul {
   
   display:grid;
   grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
   width: 100%;
    margin: 0;
    list-style: none;
    justify-content: center;
   
}

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
   SELECT
================================ */
.card select {
    width: 100%;
    padding: 8px;
    border-radius: 8px;
    border: none;
    outline: none;
    font-weight: bold;
    cursor: pointer;
    background: var(--btn-bg);
    color: var(--btn-text);
    margin-bottom: 10px;
}

select:hover {
    background: var(--btn-hover);
}

.card button {
    margin: auto;
    background: var(--btn-bg);
    color: var(--btn-text);
    border: none;
    padding: 8px 15px;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    width: 100%;
}

.card button:hover {
    background: var(--btn-hover);
}

@media (max-width: 768px) {
  
ul {
   
  align-items: center;
  display: flex;
  flex-direction: column;
}
}

@media (max-width: 480px) {
 
}
</style>