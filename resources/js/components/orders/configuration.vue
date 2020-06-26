<template>
 <div>
    <b-row>
         <v-col cols="4">
            <v-switch v-model="state" label="Estado"></v-switch><br>
        </v-col>
    </b-row>
    <b-row align="center" justify="center">
        <v-col cols="12">
            <v-btn class="text-center" color="primary" @click="cancel_order()">Guardar</v-btn>
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
          avatar:'',
          state:true
      }
  },
  methods:{
    cancel_order(){
        if(!this.state){
            axios.post('/ordenes/cancelOrder/'+this.orden.id).then(response => {
                window.location.href = window.location.origin+"/ordenes";
            }).catch(error => {
                alert("No se ha podido cancelar la orden");
            });
        }else{
            alert("Orden Guardada");
        }
    }
  },props:{
    orden: Object
  },
  mounted(){

  },
}
</script>
