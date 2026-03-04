<template>
  <h1>Users</h1>

  <table>

    <tr>
      <th>Nombre</th>
      <th>Email</th>
      <th>Password</th>
    </tr>
    <tr v-for="user in users">
      <td>{{ user.nombre }}</td>
      <td>{{ user.email }}</td>
      <td>{{ user.password }}</td>
    </tr>
  </table>
</template>
<script>



export default {
  name: "ListUsersComponent",
  props: {

  },
  data() {
    return {
      users: [
        {
          "nombre": "Juan Pérez",
          "email": "juan.perez@example.com",
          "password": "123456"
        },
        {
          "nombre": "María Gómez",
          "email": "maria.gomez@example.com",
          "password": "abcdef"
        },
        {
          "nombre": "Carlos López",
          "email": "carlos.lopez@example.com",
          "password": "qwerty"
        }
      ]
    }
  },
  computed: {},
  methods: {



  },
  async mounted() {

  },
}
</script>

<style scoped>
h1 {
  text-align: center;
}
table{
  display: flex;
  flex-direction: column;
align-items: center;
text-align: center;
border-collapse:collapse;
}
th, td {
  border: solid 3px;
  
}
</style>