<template>
  <div v-if="usuario">
    <BaseCard class="card">


      <div class="info">


        <h2>{{ usuario.email }}</h2>

        <p>
          <strong>ID:</strong> {{ usuario.id }}
        </p>

        <p>
          <strong>Rol:</strong> {{  usuario.roles.includes("ROLE_ADMIN") ? "Administrador" : "Usuario"  }}
        </p>

        <p>
          <strong>Favoritos:</strong> 
        </p>

      </div>

    </BaseCard>



  </div>
  <div v-else>
    <p>Cargando...</p>
  </div>
</template>

<script>
import BaseCard from '@/components/base/BaseCard.vue';


export default {
  name: 'UserCard',
  components: {
    BaseCard
  },
  props: {
    usuario: Object,
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
</style>