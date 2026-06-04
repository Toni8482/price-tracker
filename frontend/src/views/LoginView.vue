<template>
  <h1>Login</h1>
  <FormLogin @data-login="login" />
</template>

<script>
import FormLogin from '@/components/login/FormLogin.vue';
import { Login, getMe } from '@/services/api';

export default {
  name: 'LoginView',
  components: {
    FormLogin
  },
  data() {
    return {

    }
  },

  computed: {

  },

  methods: {
    async login(dataUser) {

      try {
        const data = await Login(dataUser);

        localStorage.setItem('token', data.token);

        const usuario = await getMe(data.token);

        localStorage.setItem('user_email', usuario.email);
        localStorage.setItem('user_id', usuario.id);
        localStorage.setItem('roles', JSON.stringify(usuario.roles));
          alert("Sesión iniciada");
        this.$router.push({ name: "HomeView" });

      } catch (error) {
        console.error(error);
        alert("Error al iniciar sesión");
      }

    }
  },

  mounted() {

  }
}
</script>

<style scoped>
h1 {
  text-align: center;
  color: var(--form-text);
}
</style>