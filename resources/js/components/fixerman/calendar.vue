<template>
  <div>
      <FullCalendar :plugins="calendarPlugins" :header ="{right: 'calcular prev today next'}" :selectable="true" :height="400" :buttonText="{today: 'Hoy',}"
            :editable="true" :events="ordenes" :config="config"    @eventClick="handleEventClick($event)"
            :customButtons="customButtons" />
        <v-row>
            <v-col cols="4">
                <div class="cancelado"></div> <span>Cancelado</span>
            </v-col>
            <v-col cols="4">
                <div class="proceso"></div> <span>En proceso</span>
            </v-col>
            <v-col cols="4">
                <div class="calificado"></div> <span>Calificado</span>
            </v-col>
        </v-row>
      <div>
          <v-app>
            <v-dialog v-model="dialog"  max-width="390">
                <v-card>
                    <v-card-title class="headline">Detalle de servicio del: {{ order.service_date }}</v-card-title>
                    <b-card-body>

                        <v-alert :color="color" border="right" class="white--text">{{ estado }}</v-alert>
                        <h5>Cliente: {{ order.name }} {{ order.lastName }}</h5>
                        <h5>Cupón: {{order.pre_coupon || "-" }}</h5>
                        <h5>Precio de Visita: {{ precio_visita }}</h5>
                        <h5>Precio: {{ price }}</h5>
                        <h5>Mano de Obra: {{ mano_de_obra }}</h5>
                        <v-layout justify-center>
                            <v-btn color="warning" dark @click="abrir_detalle(order.id)">Ver Orden</v-btn>
                        </v-layout>
                    </b-card-body>
                    <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" text @click="dialog = false">OK</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
          </v-app>
          <v-app>
            <v-dialog v-model="calculo"  max-width="390">
                <v-card>
                    <v-card-title class="headline">Cálculo de pagos</v-card-title>
                    <b-card-body>
                        <v-menu ref="inicio" v-model="inicio" :close-on-content-click="false" :return-value.sync="date" transition="scale-transition" offset-y min-width="290px">
                            <template v-slot:activator="{ on }">
                            <v-text-field v-model="computedDateFormatted" label="Inicio" readonly v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="date" no-title scrollable>
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="inicio = false">Cancel</v-btn>
                            <v-btn text color="primary" @click="$refs.inicio.save(date)">OK</v-btn>
                            </v-date-picker>
                        </v-menu>
                        <v-menu ref="fin" v-model="fin" :close-on-content-click="false" :return-value.sync="date_fin" transition="scale-transition" offset-y min-width="290px">
                            <template v-slot:activator="{ on }">
                            <v-text-field v-model="computedDateEndFormatted" label="Fin" readonly v-on="on"></v-text-field>
                            </template>
                            <v-date-picker v-model="date_fin" no-title scrollable>
                            <v-spacer></v-spacer>
                            <v-btn text color="primary" @click="fin = false">Cancel</v-btn>
                            <v-btn text color="primary" @click="$refs.fin.save(date_fin)">OK</v-btn>
                            </v-date-picker>
                        </v-menu>
                        <v-switch v-model="visita" label="Precio de Visita"></v-switch>
                        <v-switch v-model="precio_servicio" label="Precio de Servicio (Mano de obra)"></v-switch>
                        <v-switch v-model="propinas" label="Propinas"></v-switch>
                        <v-switch v-model="porcentaje" label="Porcentaje de Técnico"></v-switch>
                        <div id="resultado"></div>
                         <v-layout justify-center>
                            <v-btn color="warning" dark @click="calcular_monto()">Calcular</v-btn>
                        </v-layout>
                    </b-card-body>
                    <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" text @click="calculo = false">OK</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
          </v-app>
          <v-app>
            <v-dialog v-model="loader" hide-overlay persistent width="300">
                <v-card color="primary" dark>
                    <v-card-text>Calculando...<v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear></v-card-text>
                </v-card>
            </v-dialog>
          </v-app>
      </div>
  </div>
</template>

<style lang='scss'>
    @import '~@fullcalendar/core/main.css';
    @import '~@fullcalendar/daygrid/main.css';
    @import '~vuetify/dist/vuetify.min.css';
    #cardContent{
        overflow-y: scroll;
    }
    .v-application--wrap{
        min-height: 50px !important;
    }
    .cancelado {
        margin-top: 2%;
        width: 20px;
        height: 20px;
        background: red;
    }.proceso{
        margin-top: 2%;
        width: 20px;
        height: 20px;
        background: blue;
    }.calificado{
        margin-top: 2%;
        width: 20px;
        height: 20px;
        background: green;
    }
</style>

<script>

import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction';
import '@fullcalendar/core/locales/es'

export default {
  props:{
      fixerman:Object
  },
  components: {
    FullCalendar
  },
  data() {
    return {
      calendarPlugins: [ dayGridPlugin,interactionPlugin ],
      config: {
        locale: 'es'
      },
      dialog: false,
      calculo:false,
      inicio: false,
      fin: false,
      order:[],
      estado: '',
      color:'',
      price:'',
      mano_de_obra:'',
      precio_visita:'',
      date: new Date().toISOString().substr(0, 10),
      date_fin:new Date().toISOString().substr(0, 10),
       customButtons:{
          calcular:{text:'Calcular',click:this.openCalculo.bind(this)}
      },
      visita:'',
      precio_servicio:'',
      propinas:'',
      porcentaje:'',
      loader:false,

    }
  },mounted(){
     this.$store.dispatch('orders',this.fixerman);
  },computed:{
      ordenes(){ return this.$store.state.orders;},
      computedDateFormatted () { return this.formatDate(this.date)},
      computedDateEndFormatted () { return this.formatDate(this.date_fin)},
  },methods:{
      handleEventClick(e){
          e.jsEvent.stopPropagation();
          this.order = e.event.extendedProps.children;
          this.color = e.event.extendedProps.extendedColor;
        if(this.order.state == "PENDING"){
            this.estado = "PENDIENTE DE COTIZACIÓN";
        }
        else if(this.order.state == "FIXERMAN_NOTIFIED"){
            this.estado = "TÉCNICOS NOTIFICADOS";
        }else if(this.order.state == "ACCEPTED" || this.order.state == 'FIXERMAN_APPROVED'){
            this.estado = "TÉCNICO ASIGNADO";
        }else if(this.order.state == "CANCELLED"){
            this.estado = "CANCELADO";
        }else if(this.order.state == "DONE"){
            this.estado = "TERMINADO";
        }else if(this.order.state == "QUALIFIED"){
            this.estado = "CALIFICADO";
        }
        if(this.order.price == "waitquotation" || this.order.price == "quotation"){
            this.price = "Esperando cotización";
            this.mano_de_obra = "Esperando cotización";
        }else if(this.order.price == "CANCELLED"){
            this.price = "-";
        }else{
            this.price = this.order.price;
        }

        if(this.order.price != "waitquotation" && this.order.price != "quotation" && this.order.price != "CANCELLED"){
            this.mano_de_obra = this.order.workforce;
        }else{
            this.mano_de_obra = "-";
        }

        if(this.order.visit_price != "quotation"){
            this.precio_visita = this.order.visit_price;
        }else{
            this.precio_visita = "0";
        }
        this.dialog = true;
      },
      abrir_detalle(id){
          window.location.href = window.location.origin+"/ordenes/detalle-orden/"+id;
      },
      openCalculo(){
        this.calculo = true;
      },
      formatDate (date) {
        if (!date) return null
        const [year, month, day] = date.split('-')
        return `${day}-${month}-${year}`
      },
      calcular_monto(){
          this.loader = true;
          axios.get('/tecnicos/calcular',{params:{
            fecha_inicio:this.date,
            fecha_fin:this.date_fin,
            precio_visita:this.visita,
            precio_servicio:this.precio_servicio,
            precio_propina:this.propinas,
            porcentaje:this.porcentaje,
            id_fixerman:this.fixerman.id
          }}).then(response => {
              this.loader = false;
              let total = response.data.servicios + response.data.visita + response.data.propinas;
              $("#resultado").html('<b>Mano de obra: </b>$'+response.data.servicios+'<br><b>Visita: </b>$'+response.data.visita+'<br><b>Propinas: </b>$'+response.data.propinas+'<br><b>Total: </b>$'+total);
            }).catch(error => {
              this.loader = false;
            });
      }
  }
}

</script>
