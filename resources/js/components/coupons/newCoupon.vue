<template>
  <v-app>
    <v-card>
        <v-card-title class="headline">Nuevo Cupón</v-card-title>
        <v-alert dense border="left" type="error" v-model="error">{{ message }}</v-alert>
        <b-card-body>
            <v-col cols="12">
                <v-text-field label="Código" v-model="code"></v-text-field>
            </v-col>
            <v-col cols="12">
                <v-text-field label="Descuento" v-model="discount"></v-text-field>
            </v-col>
            <v-col cols="12">
                <b-form-radio v-model="tipo" name="some-radios" value="Porcentaje">Porcentaje</b-form-radio>
                <b-form-radio v-model="tipo" name="some-radios" value="Pesos">Pesos</b-form-radio>
            </v-col>
            <v-col cols="12">
                <v-text-field label="Responsable" v-model="keywords"></v-text-field>
                <ul v-if="results.length > 0">
                    <span v-for="result in results">
                        <li  :key="result.id" v-text="result.name+' '+result.lastName" @click="seleccionar_responsable(result.id,result.name,result.lastName)"></li>
                    </span>
                </ul>
            </v-col>
            <v-switch v-model="state" label="Estado"></v-switch>
                <v-layout justify-center>
                <v-btn color="warning" dark @click="nuevo_cupon()">Crear Cupón</v-btn>
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
   data() {
    return {
      error: false,
      code:'',
      tipo:'',
      state:true,
      discount:'',
      loader:false,
      message:'',
      responsable:null,
      keywords:null,
      results:[]
    }
  },methods:{
      abrir_detalle(id){
      },
      nuevo_cupon(){
            let check = this.checkForm();
            if(check){
                let formData = new FormData();
                formData.append('code',this.code);
                formData.append('discount',this.discount);
                formData.append('state',this.state);
                formData.append('type',this.tipo);
                formData.append('responsable',this.responsable);

              axios.post('/cupones/save',formData).then(response => {
                //   this.loader = false;
                    if(!response.data.success){
                        this.showError(response.data.message);
                    }else{
                        window.location.href = window.location.origin+"/cupones";
                    }
                }).catch(error => {
                  this.loader = false;
                });
            }else{
                this.showError("Verifique los datos ingresados");
            }
      },seleccionar_responsable(id,name,lastName){
          this.keywords = name+' '+lastName;
          this.responsable = id;
          this.results = [];
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
      },fetch() {
            axios.get('/cupones/search', { params: { keywords: this.keywords } })
                .then((response)=>{
                     this.results = response.data.users
                })
                .catch(error => {});
        }
  },watch: {
        keywords(after, before) {
            this.fetch();
        }
    },
}

</script>
