<template>
  <div class="detalles-user">
    <h1>Detalles de usuario</h1>
    <UserCard :usuario="usuario"/>
  </div>
</template>

<script>
import UserCard from "@/components/detailsUser/UserCard.vue";
import { getMe, getUsers } from "../services/api";


export default {
  name: 'UserView',
  components: {
   UserCard
  },
  data() {
    return {
     usuario: null,
      token: "",
    }
  },
 watch: {

    "$route.params.id": {
      immediate: true,
      handler(newId) {
        this.cargarUsuario(newId);

      },
    },

  },
  computed: {

  },
  methods: {

    async cargarUsuario(id) {
      this.token = localStorage.getItem('token');
       this.usuario = await getMe(this.token);

    

    }
  },

  mounted() {
   
  },
}
</script>

<style scoped>
.detalles-user {
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

h1 {
  color: var(--primary-color);
  margin-bottom: 30px;
  text-align: center;
  font-family: 'Segoe UI', sans-serif;
}

</style>