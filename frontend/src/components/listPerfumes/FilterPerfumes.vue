<template>
    <div class="filtros-content">
        <div class="seleccion_filtrado">
            <p>Genero</p>
            <label>
                <input type="radio" v-model="selectedGenre" name="genero" value="todos">
                Todos
            </label>
            <label>
                <input type="radio" v-model="selectedGenre" name="genero" value="Mujeres">
                Mujeres {{ quantityWomen }}
            </label>
            <label>
                <input type="radio" v-model="selectedGenre" name="genero" value="Hombres">
                Hombres {{ quantityMen }}
            </label>
        </div>

        <div class="seleccion_filtrado">
            <p>Tiendas</p>
            <label>
                <input type="radio" v-model="selectedWebSite" name="tienda" value="todos">
                Todos
            </label>
            <label>
                <input type="radio" v-model="selectedWebSite" name="tienda" value="perfumerias">
                Perfumerias
            </label>
            <label>
                <input type="radio" v-model="selectedWebSite" name="tienda" value="perfumesClub">
                PerfumesClub
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
</style>