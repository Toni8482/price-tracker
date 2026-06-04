<template>
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
  name: 'FormLogin',

  data() {
    return {
      user: {
        email: '',
        password: ''
      }
    }
  },

  computed: {

  },

  methods: {
    async login() {

      if (!this.user.email?.trim()) {
        alert("Introduce un email");
        return;
      }

      if (!this.user.password) {
        alert("Introduce una contraseña");
        return;
      }



      this.$emit('data-login', this.user);
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