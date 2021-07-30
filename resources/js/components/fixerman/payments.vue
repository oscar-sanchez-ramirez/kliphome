<template>
 <v-app>
    <h3>Pagos</h3>
     <table class="table">
        <thead>
            <tr>
            <th scope="col">Concepto</th>
            <th scope="col">Monto</th>
            <th scope="col">Fecha</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in payments" :key="item.id">
                <td scope="row"  v-if="item.description == 'PAGO POR SERVICIO'">
                    <a data-toggle="collapse" :href="'#collapsePayment'+item.id" role="button" aria-expanded="false" :aria-controls="'collapsePayment'+item.id">{{ item.description }}</a>
                    <div class="collapse" :id="'collapsePayment'+item.id">
                        <div class="card card-body">
                            <div class="row">
                                <b>Precio por servicio:  </b>{{ item.service_price }}
                            </div>
                            <div class="row">
                                <b>Mano de Obra:  </b>{{ item.workforce }}
                            </div>
                            <div class="row" >
                                <div class="col-md-4">
                                    %: {{ item.percent }}<br>
                                    Ganó: {{ ((item.workforce * item.percent)/100)}}
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td scope="row" v-if="item.description != 'PAGO POR SERVICIO'">
                    {{ item.description }}
                </td>
                <td>${{ item.price }}</td>
                <td>{{ item.created_at }}</td>
            </tr>
        </tbody>
    </table>

 </v-app>
</template>
<style lang='scss'>
    @import '~vuetify/dist/vuetify.min.css';
    .checked {
        color: orange !important;
    }
    .fa-star{
        color:#3333;
    }
</style>
<script>
export default{
  data(){
      return {
          payments:[]
      }
  },
  methods:{

  },props:{
    fixerman: Object
  },
  mounted(){
      axios.get('/tecnicos/payments/'+this.fixerman.id).then(response => {
            //   this.loader = false;
              this.payments = response.data.payments;
            for (let index = 0; index < payments.length; index++) {
                if(payments[index].description == "PAGO POR SERVICIO"){
                    let collapsePayment = '';
                    let th = '';
                }else{
                    let th = '';
                }
                // $("#fixerManPayments").append('<tr>'+th+'</tr>');

            }
            //   this.payments = response.data.payments;
            }).catch(error => {
            //   this.loader = false;
            });
  },
}
</script>
