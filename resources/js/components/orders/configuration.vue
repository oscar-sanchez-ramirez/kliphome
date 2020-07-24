<template>
 <div>
    <v-row>
        Estado: <span class="badge badge-danger" v-if="orden.state == 'PENDING'"> PENDIENTE DE COTIZACIÓN</span>
        <span class="badge badge-info" v-if="orden.state == 'FIXERMAN_NOTIFIED'"> TÉCNICOS NOTIFICADOS</span>
        <span class="badge badge-info" v-if="orden.state == 'ACCEPTED' || orden.state == 'FIXERMAN_APPROVED'"> TÉCNICO ACEPTÓ SOLICITUD</span>
        <span class="badge badge-danger" v-if="orden.state == 'CANCELLED'"> CANCELADO</span>
        <span class="badge badge-success" v-if="orden.state == 'DONE' || orden.state == 'FIXERMAN_DONE'"> TERMINADO</span>
        <span class="badge badge-success" v-if="orden.state == 'QUALIFIED'"> CALIFICADO</span>
    </v-row>
    <v-row>
        <v-col cols="4">
            <v-switch v-model="done" label="Marcar como terminado" :disabled="check_done"></v-switch><br>
        </v-col>
    </v-row>
    <v-row>
         <v-col cols="4">
            <v-switch v-model="state" label="Estado" :disabled="check_cancelled"></v-switch><br>
        </v-col>
    </v-row>
    <v-row align="center" justify="center">
        <v-col cols="12">
            <v-btn class="text-center" color="primary" @click="cancel_order()">Guardar</v-btn>
        </v-col>
    </v-row>
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
          done:false,
          state:true,
          check_done:true,
          check_cancelled:false
      }
  },
  methods:{
    cancel_order(){

        if(!this.state){
            axios.post('/ordenes/cancelOrder/'+this.orden.id).then(response => {
                if(this.check_done != this.done){
                    axios.post('/ordenes/markDone/'+this.orden.id).then(()=>{
                    window.location.href = window.location.origin+"/ordenes";
                    });
                }
            }).catch(error => {
                alert("No se ha podido cancelar la orden");
            });
        }else{
            if(this.check_done != this.done){
                axios.post('/ordenes/markDone/'+this.orden.id).then(()=>{
                    alert("Se notificó al cliente");
                });
            }else{
                alert("Orden Guardada");
            }
        }
    },
    async mark_done(){

    }
  },props:{
    orden: Object
  },
  mounted(){
      if(this.orden.state == 'PENDING' || this.orden.state == 'FIXERMAN_NOTIFIED' || this.orden.state == 'FIXERMAN_APPROVED' || this.orden.state == 'ACCEPTED'){
          this.check_done = false;
          this.done = false;
      }else if(this.orden.state == 'CANCELLED' || this.orden.state == 'DONE' || this.orden.state == 'QUALIFIED' || this.orden.state == 'FIXERMAN_DONE'){
          this.check_done = true;
          this.done = true;
      }
      if(this.orden.state == 'CANCELLED'){
          this.check_cancelled = true;
          this.state = false;
      }
  },
}
</script>
