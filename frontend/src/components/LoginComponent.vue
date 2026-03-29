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

      

  this.$router.push({ name: "Home" });




    }

  },
  async mounted() {

  },
}
</script>

<style scoped>
h1 {
  text-align: center;
  color: var(--form-text);
}

/* FORMULARIO */
form {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  background: var(--form-bg);
  backdrop-filter: blur(10px);
  border:1px solid var(--form-border);
  padding: 30px;
  border-radius: 14px;
  width: 300px;
  margin: 40px auto;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
}

/* LABEL */
label {
  margin-top: 10px;
  color: var(--form-label);
  font-weight: 500;
}

/* INPUTS */
input {
  width: 100%;
  padding: 10px;
  border-radius: 8px;
  border: none;
 background: var(--form-input-bg);
  color: var(--form-input-text);
}

input::placeholder {
  color: var(--form-input-placeholder);
}

/* BOTÓN */
button {
  width: 100%;
  margin-top: 15px;
  background: var(--btn-bg);
  color: var(--btn-text);
  border: none;
  padding: 10px;
  border-radius: 10px;
  cursor: pointer;
  font-weight: bold;
  transition: all 0.2s;
}

button:hover {
   background: var(--btn-hover);
  transform: translateY(-2px);
}
</style>