<template>
  
    <table>
      <thead>
        <slot name="header" />
      </thead>

      <tbody>
        <slot name="body" />
      </tbody>
    </table>
 
</template>

<script>
export default {
  name: 'BaseTable',
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
.table{
  width: 100%;
  align-items: center;
}
</style>