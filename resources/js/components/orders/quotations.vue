<template>
    <v-card>
        <v-container>
            <v-row>
                <v-btn depressed small color="primary" @click="open_modal_quotation()">Nueva</v-btn>
            </v-row>
            <v-data-table :headers="headers" :items="quotations">
            <template #item.state="{ item }"><span class="status--process" v-if="item.state == 1">Procesado</span><span class="status--denied" v-if="item.state == 0">Denegado</span></template>
            <template #item.price="{ item }">${{ item.price }} / ${{ item.workforce }}</template>
            <template #item.solution="{ item }"><span v-html="item.solution"></span></template>
            <template #item.materials="{ item }"><span v-html="item.materials"></span></template>
            <template #item.state="{ item }"><span v-if="item.state == 0" class="status--denied">Sin Pagar</span><span v-if="item.state == 1" class="status--process">Pagado</span><span v-if="item.state == 2" class="status--denied">Rechazado</span></template>
            </v-data-table>
        </v-container>
        </v-card>
</template>
<script>
export default {
    props:{
        orden:Object
    },mounted(){
        this.$store.dispatch('quotations',this.orden.id);
    },data(){
        return{
            headers: [
                {text: 'Fecha',value:'created_at'},{text: 'Servicio/Mano de obra',value:'price'},{text:'Solución',value:'solution'},{text:'Materiales',value:'materials'},{text:'Estado',value:'state'}
            ],
        }
    },computed:{
        quotations(){ return this.$store.state.quotations;},
    },methods:{
        open_modal_quotation(){
            $('.header-desktop').css('position','unset');
            this.$store.commit('set_modal_quotation',true);
        }
    }
}
</script>
