<template>
  <div>
    <h1>Productos</h1>
    <ul>
      <li v-for="product in products" :key="product.id">
        {{ product.name }} - {{ product.price }}€
      </li>
    </ul>
  </div>
</template>

<script>
import { getProducts } from './services/api';

export default {
  data() {
    return {
      products: [],
    };
  },
  async created() {
    try {
      const response = await getProducts();
      this.products = response.data;
    } catch (error) {
      console.error('Error fetching products:', error);
    }
  },
};
</script>
