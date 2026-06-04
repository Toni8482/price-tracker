<template>
  <div class="stats">
    <div class="stat-card">
      <h2>{{ quantityPerfumes }}</h2>
      <p>Perfumes disponibles</p>
    </div>
    <div class="stat-card">
      <h2>{{ quantityStores }}</h2>
      <p>Tiendas</p>
    </div>
    <div class="stat-card">
      <h2 >
        {{ mostExpensivePerfume }}€
      </h2>
     
      <p>Perfume más caro</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'StatsSection',
  props: {
    mostExpensivePerfume: {
      type: Number,
      required: true
    },
    quantityPerfumes: {
      type: Number,
      required: true
    },
    quantityStores: {
      type: Number,
      required: true
    }
  },

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
.stats {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
  justify-content: center;
  margin-bottom: 30px;
}

.stat-card {
  background: var(--stat-bg);
  backdrop-filter: blur(10px);
  color: var(--stat-text);
  padding: 20px;
  border-radius: 15px;
  text-align: center;
  width: 180px;
  border: 1px solid rgba(124, 58, 237, 0.3);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
}

.stat-card h2 {
  font-size: 2rem;
  margin: 0;
  color: var(--stat-title);
}

.stat-card p {
  margin: 5px 0 0 0;
}
</style>