<template>
  <h1>Form user</h1>

  <form @submit.prevent="crearUser">


   <label>
  Nombre:
  <input type="text" v-model="user.nombre">
</label>

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
    <button type="submit">Crear user</button>
  </form>
</template>
<script>

import { CreateUser } from '@/services/api';

export default {
  name: "FormComponent",
  props: {

  },
  data() {
    return {
      user: {
        nombre : '',
        email : '',
        password : '',

      },
      repeatPassword :"",
    }
  },
  computed: {},
  methods: {
   async crearUser() {

      if(this.user.password != this.repeatPassword){
        alert("Password no coincide.");
        return;
      }
     alert(JSON.stringify(this.user));

      await CreateUser(this.user);



     this.user.nombre = "";
     this.user.email = "";
     this.user.password = "";
     this.repeatPassword = "";
    }


  },
  async mounted() {

  },
}
</script>

<style scoped>
h1{
text-align: center;
}
form{
  display: flex;
  flex-direction: column;
  text-align: center;
  align-items: center;
}
label{
margin: 10px;
}
button{
  width: 10%;
}

</style>