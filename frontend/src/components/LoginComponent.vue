<template>
  <h1>Login</h1>

  <form @submit.prevent="login">
    <label>
      Email:
      <input type="email" v-model="user.email">
    </label>
    <label>
      Password:
      <input type="password" v-model="user.password">
    </label>

    <button type="submit">Login</button>

  </form>
</template>
<script>

import { Login, getMe } from '@/services/api';



export default {
  name: "LoginComponent",
  props: {

  },
  data() {
    return {
      user: {
        email: '',
        password: ''
      }
    }
  },
  computed: {},
  methods: {

    async login() {

      const data = await Login(this.user);
      console.log(`Token: ${data.token}`);
      localStorage.setItem('token', data.token);

      const usuario = await getMe(data.token);
      console.log(usuario);
      localStorage.setItem('user_email', usuario.email);
      localStorage.setItem('user_id', usuario.id);


    }

  },
  async mounted() {

  },
}
</script>

<style scoped>
h1 {
  text-align: center;
}

form {
  display: flex;
  flex-direction: column;
  text-align: center;
  align-items: center;
}

label {
  margin: 10px;
}

button {
  width: 10%;
}
</style>