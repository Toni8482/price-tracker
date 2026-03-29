<template>
  <h1>Users</h1>

  <table>

    <tr>

      <th>Email</th>
      <th>Id</th>
      <th></th>

    </tr>
    <tr v-for="user in users">

      <td>{{ user.email }}</td>
      <td>{{ user.id }}</td>
      <td>
        <button @click="editarUsuario(user.id)">Editar</button>
        <button @click="eliminarUsuario(user.id)">Eliminar</button>
      </td>
    </tr>
  </table>
</template>
<script>
import router from "@/router";
import { editarUsuario, eliminarUsuario, getUsers } from "../services/api";



export default {
  name: "ListUsersComponent",
  props: {

  },
  data() {
    return {
      users: [],
      token: "",
    }
  },
  computed: {},
  methods: {

    async editarUsuario(id) {
      router.push({ name: "edit-user", params: { id } });

    },

    async eliminarUsuario(id) {

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

/* TABLA */
table {
  width: 90%;
  max-width: 900px;
  margin: 30px auto;
  border-collapse: collapse;
  display: table;
  /* para que funcione como tabla real */
  color: var(--table-text);
  background: var(--table-bg);
  backdrop-filter: blur(10px);
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
}

/* CABECERA */
th {
  padding: 12px 20px;
  background: var(--table-header-bg);
  color: var(--table-header-text);
  font-weight: bold;
  text-align: center;
  border-bottom: 2px solid rgba(255, 255, 255, 0.2);
}

/* CELDAS */
td {
  padding: 10px 15px;
  text-align: center;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

/* FILA HOVER */
tr:hover {
  background: var(--table-row-hover);
  transform: translateY(0px);
  transition: background 0.2s;
}

/* BORDES */
th,
td {
  border: 1px solid rgba(124, 58, 237, 0.3);
  border-radius: 0px;
  /* bordes redondeados solo en tabla completa */
}

/* FILAS IMPARES */
tr:nth-child(odd) td {
  background: var(--table-row-odd);
}

/* FILAS PARES */
tr:nth-child(even) td {
  background: var(--table-row-even);
}

/* RESPONSIVE */
@media (max-width: 768px) {
  table {
    width: 100%;
  }

  th,
  td {
    padding: 8px 10px;
  }
}
</style>