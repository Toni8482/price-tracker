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

    login() {
      alert(JSON.stringify(this.user));
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