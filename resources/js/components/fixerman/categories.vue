<template>
 <v-app>
    <h3>Categorías Seleccionadas</h3>
    <ul id="example-1">
        <li v-for="item in categories" :key="item.id">
            {{ item.title }}
        </li>
    </ul>
    <h3>Areas Seleccionadas</h3>
    <ul id="example-1">
        <li v-for="item in delegation" :key="item.id">
            {{ item.title }}
        </li>
    </ul>

 </v-app>
</template>
<style lang='scss'>
    @import '~vuetify/dist/vuetify.min.css';
</style>
<script>
export default{
  data(){
      return {

      }
  },
  methods:{

  },props:{
    delegation: Array,
    categories: Array
  },
  mounted(){
  },
}
</script>
