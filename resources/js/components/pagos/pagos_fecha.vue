<template>
  <v-app>
      <v-row>
        <v-col cols="6">
          <v-dialog ref="dialog" v-model="modal" :return-value.sync="date" persistent width="290px">
            <template v-slot:activator="{ on, attrs }">
              <v-text-field v-model="computedDateFormatted" label="Fecha Inicio" readonly v-bind="attrs" v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker v-model="date" scrollable>
              <v-spacer></v-spacer>
              <v-btn text color="primary" @click="modal = false">Cancel</v-btn>
              <v-btn text color="primary" @click="$refs.dialog.save(date)">OK</v-btn>
            </v-date-picker>
          </v-dialog>
        </v-col>
        <v-col cols="6">
          <v-dialog ref="dialog" v-model="modal" :return-value.sync="date" persistent width="290px">
            <template v-slot:activator="{ on, attrs }">
              <v-text-field v-model="computedDateEndFormatted" label="Fecha Fin" readonly v-bind="attrs" v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker v-model="date" scrollable>
              <v-spacer></v-spacer>
              <v-btn text color="primary" @click="modal = false">Cancel</v-btn>
              <v-btn text color="primary" @click="$refs.dialog.save(date)">OK</v-btn>
            </v-date-picker>
          </v-dialog>
        </v-col>
      </v-row>
        <v-layout justify-center>
            <v-btn color="warning" id="boton_calcular" dark @click="calcular_monto()">Calcular</v-btn>
        </v-layout>

        <v-dialog v-model="loader" hide-overlay persistent width="300">
            <v-card color="primary" dark>
                <v-card-text>Calculando...<v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear></v-card-text>
            </v-card>
        </v-dialog>
        <table class="table">
        <thead>
            <tr>
                <th scope="col">Nombre</th>
                <th scope="col">Propinas</th>
                <th scope="col">Mano de obra</th>
                <th scope="col">Visita</th>
                <th scope="col">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in users" :key="item.id">
                <td scope="row"><a :href="url_detail(item.user_id)" target="_blank">{{ item.name }}</a></td>
                <td>${{ item.propinas }}</td>
                <td>${{ item.servicios }}</td>
                <td>${{ item.visita }}</td>
                <td>${{ item.visita + item.propinas + item.servicios }}</td>
            </tr>
        </tbody>
    </table>
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
    .row{
        padding:4%;
    }
    #boton_calcular{
        margin-bottom: 3%;
    }
</style>
<script>
  export default {
    data: () => ({
      date: new Date().toISOString().substr(0, 10),
      date_fin: new Date().toISOString().substr(0, 10),
      menu: false,
      modal: false,
      menu2: false,
      loader:false,
      users: [],
    }),computed:{
        computedDateFormatted () { return this.formatDate(this.date)},
        computedDateEndFormatted () { return this.formatDate(this.date_fin)},
    },methods:{
        url_detail(id){
            return window.location.origin+'/tecnicos/detalle/'+id;
        },
        formatDate (date) {
            if (!date) return null
            const [year, month, day] = date.split('-')
            return `${day}-${month}-${year}`
        },
        calcular_monto(){
            this.users = [];
            // this.loader = true;
            axios.get('/pagos/calcular',{params:{
                fecha_inicio:this.date,
                fecha_fin:this.date_fin,
            }}).then(response => {
                let users = response.data.users;
                for (let index = 0; index < users.length; index++) {
                    if(users[index] != ""){
                        this.users.push(users[index]);
                    }
                }
                }).catch(error => {
                    console.log(error);
                });
        }
    }
  }
</script>
