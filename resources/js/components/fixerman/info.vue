<template>
 <div>
    <v-alert type="success" v-if="success">
      Los datos fueron actualizados
    </v-alert>
    <b-row>
        <v-col cols="4">
            <v-img
                :src="avatar" lazy-src="https://picsum.photos/id/11/100/60" aspect-ratio="1" max-width="150" max-height="150">
                <template v-slot:placeholder>
                    <v-row align="center" justify="center">
                        <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                    </v-row>
                </template>
            </v-img>
        </v-col>
        <v-col cols="4">
            <v-text-field label="Nombre" v-model="name" hide-details="auto"></v-text-field>
        </v-col>
        <v-col cols="4">
            <v-text-field label="Apellidos" v-model="lastName" hide-details="auto"></v-text-field>
        </v-col>
        <v-col cols="4">
            <v-text-field label="Teléfono" v-model="phone" hide-details="auto"></v-text-field>
        </v-col>
        <v-col cols="4">
            <v-text-field label="Email" v-model="email" hide-details="auto"></v-text-field>
        </v-col>
        <v-col cols="4">
            <v-text-field label="Código" v-model="code" hide-details="auto"></v-text-field>
        </v-col>
         <v-col cols="4">
            <v-switch v-model="state" label="Estado"></v-switch><br>
        </v-col>
    </b-row>
    <b-row align="center" justify="center">
        <v-col cols="12">
            <v-btn class="text-center" color="primary" @click="guardar_datos">Actualizar</v-btn>
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
    guardar_datos(){
        let formData = new FormData();
        formData.append('name',this.name);
        formData.append('lastName',this.lastName);
        formData.append('phone',this.phone);
        formData.append('email',this.email);
        formData.append('code',this.code);
        formData.append('state',this.state);
        axios.post('/tecnicos/guardar_datos/'+this.fixerman.id,formData).then(response => {
            if(response.data.success == true){
                this.success = true;
                setTimeout(() => {
                    this.success = false;
                }, 3000);
            }

        }).catch(error => {
        });
    }
  },props:{
    fixerman: Object
  },
  mounted(){
    this.name = this.fixerman.name;
    this.lastName = this.fixerman.lastName;
    this.phone = this.fixerman.phone;
    this.email = this.fixerman.email;
    this.code = this.fixerman.code;
    this.avatar = this.fixerman.avatar;
    if(this.fixerman.state == "1"){
        this.state = true;
    }else{
        this.state = false;
    }
  },
}
</script>
