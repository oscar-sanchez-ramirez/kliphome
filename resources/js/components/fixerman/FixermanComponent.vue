<template>
  <b-container fluid style="height: 100%;margin-top:20px;">
      <h5>Técnico : {{ fixerman.name }} {{ fixerman.lastName }}</h5>
   <v-tabs>
    <v-tab href="#tab-1">Ordenes</v-tab>
    <v-tab href="#tab-2">Ficha</v-tab>
    <v-tab href="#tab-3">Categorías</v-tab>
    <v-tab href="#tab-4">Calificaciones</v-tab>
    <v-tab href="#tab-5">Pagos</v-tab>
    <v-tab href="#tab-6">Datos</v-tab>
    <v-tab href="#tab-7">Imagenes</v-tab>
    <v-tab-item value="tab-1">
        <v-card flat tile>
            <calendar-component :fixerman="fixerman"></calendar-component>
        </v-card>
    </v-tab-item>
    <v-tab-item value="tab-2">
        <v-card flat tile>
            <v-card-text><stats-component :ficha="ficha" :fixerman="fixerman"></stats-component></v-card-text>
        </v-card>
    </v-tab-item>
    <v-tab-item value="tab-3">
        <v-card flat tile>
            <v-card-text><categories-component :categories="categories" :delegation="delegation"></categories-component></v-card-text>
        </v-card>
    </v-tab-item>
    <v-tab-item value="tab-4">
        <v-card flat tile>
            <v-card-text><reviews-component :categories="categories" :delegation="delegation" :fixerman="fixerman"></reviews-component></v-card-text>
        </v-card>
    </v-tab-item>
    <v-tab-item value="tab-5">
        <v-card flat tile>
            <v-card-text><payments-component :fixerman="fixerman"></payments-component></v-card-text>
        </v-card>
    </v-tab-item>
     <v-tab-item value="tab-6">
        <v-card flat tile>
            <v-card-text><info-component :fixerman="fixerman"></info-component></v-card-text>
        </v-card>
    </v-tab-item>
    <v-tab-item value="tab-7">
        <v-card flat tile>
            <v-card-text><images-component :fixerman="fixerman"></images-component></v-card-text>
        </v-card>
    </v-tab-item>
  </v-tabs>
  </b-container>
</template>
<style lang='scss'>
    @import '~vuetify/dist/vuetify.min.css';
</style>
<script>
export default{
  props:{
    fixerman: Object,
    ficha:Object,
    categories:Array,
    delegation:Array
  },
  mounted(){
  },
  methods:
  {
  },
  computed:{
  }
}
</script>
