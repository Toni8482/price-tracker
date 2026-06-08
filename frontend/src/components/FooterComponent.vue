<template>
    <footer class="footer">
        <div class="footer-content">
            <p>© 2026 Essence. Descubre, compara y encuentra el mejor precio para tu próxima fragancia.</p>

          
        </div>
    </footer>
</template>

<script>


export default {
  name: "Footer",
  props: {
    
  },
  data() {
    return {
     


    }
  },


  methods: {
   
  },
 async mounted() {
  

  },
}
</script>

<style scoped>
.footer {
    padding: 20px;
    border-top: 1px solid var(--card-border);
    background: var(--card-bg);
    margin-top: 20px;
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    text-align: center;
}

nav {
    margin-top: 10px;
}

nav a {
    margin: 0 10px;
    text-decoration: none;
    color: var(--text-color);
}
</style>