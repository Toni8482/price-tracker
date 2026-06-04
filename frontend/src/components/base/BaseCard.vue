<template>
  <div class="base-card">
     <slot/>
  </div>
</template>

<script>
export default {
  name: 'BaseCard',
  props: {

  },
  data() {
    return {

    }
  },

  computed: {
  
  },

  methods: {
 
  },

  mounted() {

  }
}
</script>

<style scoped>

.base-card {
  background: var(--card-bg);
  backdrop-filter: blur(10px);
  border-radius: 15px;
  padding: 15px;
  text-align: center;
  width: 200px;
  border: 1px solid var(--card-border);
  box-shadow: 0 10px 25px var(--card-shadow);
  display: flex;
  flex-direction: column;
  align-items: center;
  transition: transform 0.25s, box-shadow 0.25s;
}

</style>