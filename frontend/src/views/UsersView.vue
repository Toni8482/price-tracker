<template>
  <div class="">
    <h1>Usuarios</h1>
    <div class="table-wrapper">
      <TableUsers v-if="users.length > 0" @id="deleteUser" :users="users" />
      <div v-else></div>
    </div>
  </div>
</template>

<script>
import TableUsers from '@/components/listUsers/TableUsers.vue';
import { eliminarUsuario, getUsers } from "../services/api";
export default {
  name: 'UsersView',
  components: {
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
  margin-bottom: 20px;
}

.table-wrapper {
 
  justify-content: center;
padding: 5px;
}

@media (max-width: 768px) {
  .table-wrapper {

    overflow-x: auto;
  }
}

@media (max-width: 480px) {}
</style>