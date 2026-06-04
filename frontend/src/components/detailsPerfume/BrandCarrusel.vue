<template>


    <div class="carrusel-container" v-if="perfumesMismaMarca.length">

        <swiper :slides-per-view="4" :space-between="20" :breakpoints="{
            320: {
                slidesPerView: 1
            },
            640: {
                slidesPerView: 2
            },
            1024: {
                slidesPerView: 4
            }
        }">

            <swiper-slide v-for="perfume in perfumesMismaMarca" :key="perfume.id">

                <div class="perfume-card">

                    <img :src="perfume.precio_contenido?.[0]?.image_url_precio_contenido ||
                        perfume.imagen_url
                        " class="perfume-img" />

                    <img :src="perfume.store_logo" class="logo-tienda">

                    <h3>{{ perfume.nombre }}</h3>

                    <p>{{ perfume.concentracion }}</p>

                    <button @click="cargarPerfume(perfume.id, )">
                        Detalles
                    </button>

                </div>

            </swiper-slide>

        </swiper>

    </div>
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';


export default {
    name: 'BrandCarrusel',
    props: {
        perfumesMismaMarca: Array,
    },
    data() {
        return {

        }
    },
    components: {
        Swiper,
        SwiperSlide
    },
    computed: {

    },

    methods: {
        cargarPerfume(idPerfume) {
             this.$router.push(`/perfume/${idPerfume}`);
        }
    },

    mounted() {

    }
}
</script>

<style scoped>
.carrusel-container {
    width: 95%;
    max-width: 1300px;
    margin: 30px auto;
    position: relative;
    z-index: 1;
}

.perfume-card {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.05), rgba(255, 255, 255, 0.02));
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 25px 20px;
    text-align: center;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(212, 175, 55, 0.2);
    position: relative;
    overflow: hidden;
}


.perfume-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 30px 50px rgba(0, 0, 0, 0.4);
    border-color: rgba(212, 175, 55, 0.5);
}

.perfume-img {
    width: 100%;
    height: 220px;
    object-fit: contain;
    margin-bottom: 20px;
    transition: transform 0.4s ease;
    filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.2));
}

.perfume-card:hover .perfume-img {
    transform: scale(1.05);
}



.perfume-card h3 {
    font-size: 1rem;
    margin: 15px 0 8px;
    color: var(--semiyelow-text);
    font-weight: 600;
    font-family: 'Montserrat', sans-serif;
     width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.perfume-card p {
    font-size: 0.85rem;
    color: var(--semiyelow-text);
    margin-bottom: 20px;
    font-family: 'Montserrat', sans-serif;
     width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}




.perfume-card button {
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

.perfume-card button:hover {
    background: var(--btn-hover);
}
</style>