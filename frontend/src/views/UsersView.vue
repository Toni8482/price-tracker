<template>
  <div class="">
   <h1>Users</h1>
   <TableUsers v-if="users.length > 0" @id="deleteUser" :users="users"/>
   <div v-else></div>
  </div>
</template>

<script>
import TableUsers from '@/components/listUsers/TableUsers.vue';
import { eliminarUsuario, getUsers } from "../services/api";
export default {
  name: 'UsersView',
components:{
TableUsers
},
  data() {
    return {
      users: [],
      token: "",
    }
  },

  computed: {
  
  },

  methods: {
   

    async deleteUser(id) {

      await eliminarUsuario(this.token, id);
      alert("Usuario eliminado");
      this.users = await getUsers(this.token);
    }

  },
  async mounted() {
    this.token = localStorage.getItem('token');
    this.users = await getUsers(this.token);
  },
}
</script>

<style scoped>
h1 {
  text-align: center;
  color: var(--form-text);
}
</style>