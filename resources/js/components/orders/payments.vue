<template>
    <v-card>
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
        payments:Array
    },mounted(){
    },data(){
        return{
            headers: [
                {text: 'Fecha',value:'created_at'},{text: 'Concepto',value:'description'},{text:'Cod. Pago',value:'code_payment'},{text:'Monto',value:'price'},{text:'Estado',value:'state'}
            ],
        }
    }
}
</script>
