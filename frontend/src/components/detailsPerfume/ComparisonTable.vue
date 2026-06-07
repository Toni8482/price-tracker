<template>
  <h2>Comparar precios</h2>
<div class="table-wrapper">
    <BaseTable class="comparison-table" v-if="perfumesComparados.length">
      <template #header>
        <tr>
          <th>Imagen</th>
          <th>Marca</th>
          <th>Nombre</th>
          <th>Concentración</th>
          <th>Formato</th> 
          <th>Precio</th>
          <th>Tienda</th>
       
         
        </tr>
      </template>
      <template #body>

        <tr v-for="item in perfumesComparados" :key="item.id_contenido" :class="{ 'mejor-precio': item.esMejorPrecio }">
          <td>
             <button  @click="cargarPerfume(item.id,item.id_contenido)">
            <img :src="item.imagen" alt="Imagen perfume" />
            </button>
            
          </td>
          <td>{{ item.marca }}</td>
          <td>{{ item.nombre }}</td>
          <td>{{ item.concentracion }}</td>
          <td>{{ item.formato }}</td> 
          <td>{{ item.precio }} €</td>
          <td>
             <button @click="abrirNuevaPestana(item.perfume_url)">
            <img :src="item.store_logo" alt="Logo tienda" style="height: 30px" />
              </button>
          </td>
        
         
        </tr>
      </template>
    </BaseTable>
    <span v-else>
      No hay perfumes para comparar
    </span>
</div>
 
</template>

<script>
import BaseTable from '@/components/base/BaseTable.vue';

export default {
  name: 'ComparisonTable',
  components: {
    BaseTable
  },
  props: {
    perfumesComparados: Array
  },

  data() {
    return {

    }
  },

  computed: {

  },

  methods: {
 abrirNuevaPestana(url) {
      window.open(url, "_blank");
    },
      cargarPerfume(id,idVariant) {
           
              this.$router.push({
                name: "detalle-perfume-variante", params: {
                    id: id,
                    idVariante: idVariant
                }
            });
        }
  },

  mounted() {

  }
}
</script>

<style scoped>
 .table-wrapper {

   justify-content: center;

}
.comparison-table {
  width: 95%;
  max-width: 1300px;
  margin: 20px 0;
  overflow-x: auto;
  position: relative;
  z-index: 1;
 
}


th {
  background: linear-gradient(135deg, #0a0a0a, #1a1a2e);
  color: #d4af37;
  font-size: 0.85rem;
  font-weight: 600;
  padding: 18px 12px;
  text-transform: uppercase;
  letter-spacing: 2px;
  font-family: 'Montserrat', sans-serif;
  border-bottom: 1px solid rgba(212, 175, 55, 0.3);
}

td {
  background: transparent;
  border-bottom: 1px solid rgba(212, 175, 55, 0.1);
  padding: 15px 10px;
  vertical-align: middle;
  transition: all 0.3s ease;
  color: var(--semiyelow-text);
  font-family: 'Montserrat', sans-serif;
}

tr:hover td {
  background: rgba(212, 175, 55, 0.08);
  color: var(--semiyelow-text);
}

td img {
  width: 70px;
  height: 70px;
  object-fit: contain;
  border-radius: 12px;
  background: rgba(0, 0, 0, 0.3);
  padding: 8px;
  transition: all 0.3s ease;
}

td img:hover {
  transform: scale(1.05);
}

td img[alt="Logo tienda"] {
  width: 100%;
  height:  100%;
  background: var(--store-card-bg);
 cursor: pointer;
  
}




/* Botones premium generales */
button {
  margin: 5px 8px 5px 0;
 background-color: transparent;
  border:none;
 
 
  cursor: pointer;
  
  border-radius: 12px;
  transition: all 0.3s ease;
 
  position: relative;
  overflow: hidden;
}



button:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 30px rgba(212, 175, 55, 0.4);
  
}

button:active {
  transform: translateY(1px);
}
@media (max-width: 768px) {
  .table-wrapper {
  width: 95%;
  overflow-x: auto;
}

}

@media (max-width: 480px) {
 
}

</style>