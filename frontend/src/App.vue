<template>
   <MenuComponent></MenuComponent>
 <router-view></router-view>

</template>

<script>


import MenuComponent from './components/MenuComponent.vue';


export default {
name: "App",
  components: {
    
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
