<template>
    <div class="parallax-section">
        <div class="parallax-content">
            <h1>Bienvenido a Essence</h1>
            <p>Encuentra tus perfumes favoritos al mejor precio</p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'HeroSection',

    data() {
        return {

        }
    },

    computed: {

    },

    methods: {

    },

    mounted() {

    }
}
</script>

<style scoped>
.parallax-section {
    background-image: url('https://images.unsplash.com/photo-1541643600914-78b084683601');
    background-attachment: fixed;
    /* ← Clave del parallax */
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    height: 40vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    width: 100%;
    top: 0;
    margin-bottom: 50px;
}

.parallax-content {
    color: var(--text-color);
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    background-color:var(--bg-color);
    border-radius: 25px;
    padding: 20px;
}

 h1 {
    font-size: 3rem;
    font-family: 'Cormorant Garamond', serif;
}

</style>