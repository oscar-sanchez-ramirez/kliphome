<template>
    <v-card>
        <v-row>
            <v-btn depressed small color="primary" @click="open_modal_payment()">Nueva</v-btn>
        </v-row>
        <v-card-text>
            <v-container>
                <v-data-table
                    :headers="headers"
                    :items="payments"
                >
                <template #item.state="{ item }"><span class="status--process" v-if="item.state == 1">Procesado</span><span class="status--denied" v-if="item.state == 0">Denegado</span></template>
                <template #item.price="{ item }">${{ item.price }}</template>
                </v-data-table>
            </v-container>
        </v-card-text>
        </v-card>
</template>
<script>
export default {
    props:{
        orden:Object
    },mounted(){
        this.$store.dispatch('payments',this.orden.id);
    },computed:{
        payments(){ return this.$store.state.payments;},
    },data(){
        return{
            headers: [
                {text: 'Fecha',value:'created_at'},{text: 'Concepto',value:'description'},{text:'Cod. Pago',value:'code_payment'},{text:'Monto',value:'price'},{text:'Estado',value:'state'}
            ],
        }
    },methods:{
        open_modal_payment(){
            $('.header-desktop').css('position','unset');
            this.$store.commit('set_modal_payment',true);
        }
    }
}
</script>
