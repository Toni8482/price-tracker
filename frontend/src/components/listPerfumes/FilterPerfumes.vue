<template>
    <div class="filtros-content">
        <div class="seleccion_filtrado">
            <p>Genero</p>
            <label>
                <input type="radio" v-model="selectedGenre" name="genero" value="todos">
                Todos
            </label>
            <label>
                <input type="radio" v-model="selectedGenre" name="genero" value="Mujer">
                Mujeres {{ quantityWomen }}
            </label>
            <label>
                <input type="radio" v-model="selectedGenre" name="genero" value="Hombre">
                Hombres {{ quantityMen }}
            </label>
        </div>

        <div class="seleccion_filtrado">
            <p>Tiendas</p>
            <label>
                <input type="radio" v-model="selectedWebSite" name="tienda" value="todos">
                Todos
            </label>

            <label v-for="tienda in tiendas" :key="tienda.id">
                <input type="radio" v-model="selectedWebSite" name="tienda" :value="tienda.name">
                {{ tienda.name }}
            </label>
        </div>
    </div>

</template>

<script>
export default {
    name: 'FilterPerfumes',
    props: {
        quantityWomen: Number,
        quantityMen: Number,
        tiendas: Array
    },
    data() {
        return {
            selectedGenre: "todos",
            selectedWebSite: "todos"
        }
    },
    watch: {
        selectedGenre() {
            this.emitFilters();
        },

        selectedWebSite() {
            this.emitFilters();
        }
    },
    computed: {

    },

    methods: {
        emitFilters() {
            this.$emit('filters-changed', {
                genre: this.selectedGenre,
                webSite: this.selectedWebSite
            });

        }
    },

    mounted() {

    }
}
</script>

<style scoped>
.filtros-content {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 30%;
    margin: 20px;
    align-items: center;
    position: sticky;
    top: 150px;
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
    width: 80%;
    
}


@media (max-width: 768px) {
  
.filtros-content {
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
    margin: 20px;
    align-items: center;
    position:static;
   
}
.seleccion_filtrado {
   
}
}

@media (max-width: 480px) {
 
}
</style>