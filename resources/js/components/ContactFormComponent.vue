<template>
  <v-form class="my-3 mx-2">
      <v-text-field class="text-center"
        type="text" v-model="querySearch"
         placeholder="Buscar Contacto..."></v-text-field>
  </v-form>
</template>

<script>
export default {
  computed:{
    querySearch:{
      get(){
        return this.$store.state.querySearch;
      },
      set(value){
        this.$store.commit('newQuerySearch',value);
      }
    }
  }
}
</script>
