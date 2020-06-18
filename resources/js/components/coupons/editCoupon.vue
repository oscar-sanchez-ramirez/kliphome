<template>
  <v-app>
    <v-card>
        <v-card-title class="headline">Editar Cupón</v-card-title>
        <v-alert dense border="left" type="error" v-model="error">{{ message }}</v-alert>
        <b-card-body>
            <v-col cols="12">
                <v-text-field label="Código" v-model="code"></v-text-field>
            </v-col>
            <v-col cols="12">
                <v-text-field label="Descuento" v-model="discount"></v-text-field>
            </v-col>
            <v-switch v-model="state" label="Estado"></v-switch>
                <v-layout justify-center>
                <v-btn color="warning" dark @click="actualizar_cupon()">Actualizar Cupón</v-btn>
            </v-layout>
        </b-card-body>
    </v-card>
  </v-app>
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


export default {
    props:{
        coupon:Object
    },mounted(){
        this.code = this.coupon.code;
        this.discount = this.coupon.discount;
        this.state = this.coupon.state;
    },
   data() {
    return {
      error: false,
      code:'',
      state:true,
      discount:'',
      loader:false,
      message:''
    }
  },methods:{
      abrir_detalle(id){
      },
      actualizar_cupon(){
            let check = this.checkForm();
            if(check){
                let formData = new FormData();
                formData.append('code',this.code);
                formData.append('discount',this.discount);
                formData.append('state',this.state);
                formData.append('id',this.coupon.id);

              axios.post('/cupones/update',formData).then(response => {
                    if(!response.data.success){
                        this.showError(response.data.message);
                    }else{
                        window.location.href = window.location.origin+"/cupones";
                    }
                }).catch(error => {
                  this.showError("Hubo un error inesperado, intente más tarde");
                });
            }else{
                this.showError("Verifique los datos ingresados");
            }
      },
      checkForm(){
          if(this.code != "" && this.discount != "" && this.isNumeric(this.discount)){
              return true;
          }else{
              return false;
          }
      },
      isNumeric: function (n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
      },
      showError(error){
        this.error = true;
        this.message = error;
        setTimeout(() => {
            this.error = false;
        }, 3000);
      }
  }
}

</script>
