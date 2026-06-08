<template>
  <div class="">
    <h1>{{ titulo }}</h1>
    <FormRegister @new-user="register" :btn-submit="btnSubmit" :user="user" :esAdmin="esAdmin"/>
  </div>
</template>

<script>
import FormRegister from '@/components/register/FormRegister.vue';
import { CreateUser, getUsers, editarUsuario, isAdmin } from '@/services/api';

export default {
  name: 'RegisterView',
  components: {
    FormRegister
  },

  data() {
    return {
      user: {
        id: '',
        email: '',
        password: '',
        roles: ['ROLE_USER']
      },

      idRoute: null,
      token: null,
      btnSubmit: 'Crear usuario',
      titulo: 'REGISTRO',
      esAdmin: false
    }
  },

  computed: {

  },

  methods: {
    async register(newUser) {

      if (this.idRoute) {
        try {
          await editarUsuario(this.token, newUser);
          alert("Usuario editado");

          this.user.id = "";
          this.user.nombre = "";
          this.user.email = "";
          this.user.password = "";
          this.user.roles = ['ROLE_USER'];
        } catch (error) {
          alert("Error al actualizar el usuario");
        }

      } else {

        try {
          const resultado = await CreateUser(this.user);

          alert("Usuario creado correctamente");

          console.log(resultado);


          this.user.id = "";
          this.user.nombre = "";
          this.user.email = "";
          this.user.password = "";
          this.user.roles = ['ROLE_USER'];
        } catch (error) {

          alert("Error al crear el usuario");

          console.error(error);
        }
      }

    }
  },

  async mounted() {
    this.token = localStorage.getItem('token');
    this.idRoute = this.$route.params.id;
    if (this.idRoute) {
      this.titulo = "ACTUALIZAR"
      this.btnSubmit = "Actualizar usuario";
      let usuarios = await getUsers(this.token);

      this.user = usuarios.find(u => u.id == this.idRoute);
      console.log("Usuario: " + JSON.stringify(this.user));

    }
    this.esAdmin = isAdmin();
  },
}
</script>

<style scoped>
h1 {
  text-align: center;
  color: var(--form-text);
}
</style>