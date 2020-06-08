<template>
 <v-app>
    <v-alert type="success" v-if="success">
      Los datos fueron actualizados
    </v-alert>
    <b-row>
        <v-col cols="4">
            <v-switch v-model="prueba_psicologica" label="Prueba Psicologica"></v-switch><br>
        </v-col>
        <v-col cols="4">
            <v-switch v-model="acuerdo_laboral" label="Acuerdo Laboral"></v-switch><br>
        </v-col>
        <v-col cols="4">
            <v-switch v-model="comprobante_domicilio" label="Comprobante de domicilio"></v-switch><br>
        </v-col>
        <v-col cols="4">
            <v-switch v-model="asistencia_entrevista" label="Asistencia a a entrevista"></v-switch><br>
        </v-col>
        <v-col cols="4">
            <v-switch v-model="copia_dni" label="Copia de identificación oficial"></v-switch><br>
        </v-col>
        <v-col cols="4">
            <v-switch v-model="foto" label="Foto"></v-switch><br>
        </v-col>
        <v-col cols="4">
            <v-switch v-model="kit_bienvenida" label="Kit de bienvenida"></v-switch><br>
        </v-col>
        <v-col cols="4">
            <v-text-field label="Porcentaje" v-model="percent" hide-details="auto"></v-text-field>
        </v-col>
    </b-row>
    <b-row align="center" justify="center">
        <v-col cols="12">
            <v-btn class="text-center" color="primary" @click="guardar_ficha">Actualizar</v-btn>
        </v-col>
    </b-row>
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
export default{
  data(){
      return {
          success:false,
          prueba_psicologica:false,
          acuerdo_laboral:false,
          comprobante_domicilio:false,
          asistencia_entrevista:false,
          copia_dni:false,
          foto:false,
          kit_bienvenida:false,
          percent:0
      }
  },
  methods:{
    guardar_ficha(){
        let formData = new FormData();
        formData.append('acuerdo_laboral',this.acuerdo_laboral);
        formData.append('prueba_psicologica',this.prueba_psicologica);
        formData.append('comprobante_domicilio',this.comprobante_domicilio);
        formData.append('asistencia_entrevista',this.asistencia_entrevista);
        formData.append('copia_dni',this.copia_dni);
        formData.append('foto',this.foto);
        formData.append('kit_bienvenida',this.kit_bienvenida);
        formData.append('percent',this.percent);
        formData.append('fixerman_id',this.fixerman.id);
        axios.post('/tecnicos/guardar_ficha',formData).then(response => {
            console.log(response);
            if(response.data.success == true){
                this.success = true;
                setTimeout(() => {
                    this.success = false;
                }, 3000);
            }
            // this.loader = false;

        }).catch(error => {
            // this.loader = false;
        });
    }
  },props:{
    fixerman: Object,
    ficha: Object
  },
  mounted(){
      if(this.ficha.acuerdo_laboral != "N" && this.ficha.acuerdo_laboral != "false"){
          this.acuerdo_laboral = true;
      }
      if(this.ficha.asistencia_entrevista != "N" && this.ficha.asistencia_entrevista != "false"){
          this.asistencia_entrevista = true;
      }
      if(this.ficha.comprobante_domicilio != "N" && this.ficha.comprobante_domicilio != "false"){
          this.comprobante_domicilio = true;
      }
      if(this.ficha.copia_dni != "N" && this.ficha.copia_dni != "false"){
          this.copia_dni = true;
      }
      if(this.ficha.foto != "N" && this.ficha.foto != "false"){
          this.foto = true;
      }
      if(this.ficha.kit_bienvenida != "N" && this.ficha.kit_bienvenida != "false"){
          this.kit_bienvenida = true;
      }
      if(this.ficha.prueba_psicologica != "N" && this.ficha.prueba_psicologica != "false"){
          this.prueba_psicologica = true;
      }
      this.percent = this.ficha.percent;
  },
}
</script>
