<template>
  <div v-if="usuario">
    <BaseCard class="card">


     


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


           <button @click="eliminarUsuario(usuario.id)">Eliminar cuenta</button>
     


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
   eliminarUsuario(id) {

            this.$emit("id", id);
        }
  },

  mounted() {

  }
}
</script>

<style scoped>
.card{
  width: 100%;
}

.card h2 {
  text-decoration: underline;
}


.card p {
 text-align: left;
 width: 100%;
 margin: 10px;
}

.card button {
 
  background: var(--btn-bg);
  color: var(--btn-text);
  border: none;
  padding: 8px 15px;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.2s;
  width: 100%;
  margin-top: 20px;
}

.card button:hover {
  background: var(--btn-hover);
}
</style>