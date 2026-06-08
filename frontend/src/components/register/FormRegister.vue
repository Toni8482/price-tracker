<template>
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

    <label v-if="esAdmin" for="role">
  Privilegios:

    <select id="role" v-model="user.roles">
      <option :value="['ROLE_USER']" >User</option>
      <option :value="['ROLE_ADMIN']">Admin</option>
    </select>

    </label>
    <button type="submit">{{ btnSubmit }}</button>
  </form>
</template>

<script>


export default {
  name: 'FormRegister',
  props: {
    btnSubmit: String,
    user: Object,
    esAdmin:Boolean
  },
  data() {
    return {
      repeatPassword: "",
    }
  },

  computed: {

  },

  methods: {
    crearUser() {

      if (!this.user.email?.trim()) {
        alert("Introduce un email");
        return;
      }

      if (!this.user.password) {
        alert("Introduce una contraseña");
        return;
      }


      if (this.user.password != this.repeatPassword) {
        alert("Password no coincide.");
        return;
      }

      this.$emit("new-user", this.user);
    
 this.repeatPassword = "";
    }
  },

  mounted() {

  }
}
</script>

<style scoped>
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
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
}

/* LABEL */
label {
  margin-top: 10px;
  color: var(--form-label);
  font-weight: 500;
}

/* INPUTS y SELECT */
input,
select {
  width: 100%;
  padding: 10px;
  border-radius: 8px;
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
input:focus,
select:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(124, 58, 237, 0.6);
}
</style>