<template>
 <div>
    <v-alert type="success" v-if="success">
      Los datos fueron actualizados
    </v-alert>
    <b-row>
        <v-col cols="12" v-if="fixerman.gallery.length == 0">
            <h5>No se adjuntaron imágenes</h5>
        </v-col>
        <v-col cols="4" v-for="item in fixerman.gallery" :key="item.id">
            <h5><b>{{ text_transform(item.type) }}</b></h5>
            <v-img
                :src="item.path" lazy-src="https://picsum.photos/id/11/100/60" aspect-ratio="1" max-width="150" max-height="150">
                <template v-slot:placeholder>
                    <v-row align="center" justify="center">
                        <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                    </v-row>
                </template>
            </v-img>
        </v-col>

    </b-row>

 </div>
</template>
<style lang='scss'>
    @import '~vuetify/dist/vuetify.min.css';
    #cardContent{
        overflow-y: scroll;
    }
    .v-application--wrap{
        min-height: 50px !important;
    }
</style>
<script>
export default{
  data(){
      return {
          success:'',
          name:'',
          lastName:'',
          phone:'',
          email:'',
          code:'',
          avatar:'',
          state:true
      }
  },
  methods:{
    text_transform(text){
        switch (text) {
            case 'identificacion_oficial':
                return 'Identificación Oficial:'
                break;
            case 'comprobante_domicilio':
                return 'Comprobante de Domicilio:'
                break;
            default:
                return 'Prueba Psicométrica:'
                break;
        }
    }
  },props:{
    fixerman: Object
  },
  mounted(){
    this.name = this.fixerman.name;
    this.lastName = this.fixerman.lastName;
    if(this.fixerman.state == "1"){
        this.state = true;
    }else{
        this.state = false;
    }
  },
}
</script>
