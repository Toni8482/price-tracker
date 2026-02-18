<template>
   <MenuComponent></MenuComponent>
 <router-view></router-view>

</template>

<script>

import ListaLibros from './components/ListaLibros.vue'
import MenuComponent from './components/MenuComponent.vue';


export default {

  components: {
    ListaLibros,
    MenuComponent
  },
  data() {
    return {
    
    };
  },
  
};
</script>

<style>
body{
  margin: 0px;
  padding: 0px;
}
  
</style>
