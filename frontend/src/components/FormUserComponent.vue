<template>
  <h1>REGISTER</h1>

  <form @submit.prevent="crearUser">


 

    <label>
      Email:
      <input type="email" v-model="user.email">
    </label>

    <label>
      Password:
      <input type="password" v-model="user.password">
    </label>

    <label>
      Repetir password:
      <input type="password" v-model="repeatPassword">

    </label>
    <button type="submit">{{ btnSubmit }}</button>
  </form>
</template>
<script>

import { CreateUser, getUsers, editarUsuario } from '@/services/api';

export default {
  name: "FormComponent",
  props: {

  },
  data() {
    return {
      user: {
        id: '',
        email: '',
        password: '',
      },
      repeatPassword: "",
      idRoute: null,
      token: null,
      btnSubmit: 'Crear user',
    }
  },
  computed: {},
  methods: {
    async crearUser() {

      if (this.user.password != this.repeatPassword) {
        alert("Password no coincide.");
        return;
      }

      if (this.idRoute) {
        await editarUsuario(this.token, this.user);
         alert("Usuario editado");
      } else {
        await CreateUser(this.user);
        alert("Usuario registrado");
      }



      this.user.id = "";
      this.user.nombre = "";
      this.user.email = "";
      this.user.password = "";
      this.repeatPassword = "";
    }


  },
  async mounted() {
    this.token = localStorage.getItem('token');
    this.idRoute = this.$route.params.id;
    if (this.idRoute) {
      this.btnSubmit = "Editar user";
      let usuarios = await getUsers(this.token);

      this.user = usuarios.find(u => u.id == this.idRoute);

    }
  },
}
</script>

<style scoped>
/* ================================
   FORMULARIO
================================ */
h1 {
  text-align: center;
  color: var(--form-text);
}

form {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 12px;
  background: var(--form-bg);
  backdrop-filter: blur(10px);
  border: 1px solid var(--form-border);
  padding: 30px;
  border-radius: 14px;
  width: 300px;
  margin: 40px auto;
  box-shadow: 0 10px 25px rgba(0,0,0,0.4);
}

/* LABEL */
label {
  margin-top: 10px;
  color: var(--form-label);
  font-weight: 500;
}

/* INPUTS y SELECT */
input, select {
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

/* INPUT FOCUS */
input:focus, select:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(124,58,237,0.6);
}
</style>