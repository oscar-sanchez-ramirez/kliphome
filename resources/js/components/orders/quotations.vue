<template>
    <div>
        <v-card>
            <v-alert dense border="left" type="error" v-model="error">{{ message }}</v-alert>
            <v-container>
                <v-row>
                    <v-btn depressed small color="primary" @click="open_modal_quotation()">Nueva</v-btn>
                </v-row>
                <v-data-table :headers="headers" :items="quotations">
                <template #item.state="{ item }"><span class="status--process" v-if="item.state == 1">Procesado</span><span class="status--denied" v-if="item.state == 0">Denegado</span></template>
                <template #item.price="{ item }">${{ item.price }} / ${{ item.workforce }}</template>
                <template #item.solution="{ item }"><span v-html="item.solution"></span></template>
                <template #item.materials="{ item }"><span v-html="item.materials"></span></template>
                <template #item.state="{ item }"><span v-if="item.state == 0" class="status--denied">Sin Pagar <v-btn depressed small @click="show_confirm(item.id)"><i class="fa fa-trash"></i></v-btn></span><span v-if="item.state == 1" class="status--process">Pagado</span><span v-if="item.state == 2" class="status--denied">Rechazado</span></template>
                </v-data-table>
            </v-container>
        </v-card>
        <v-dialog v-model="dialog" persistent max-width="330">
            <v-card>
                <v-card-title class="headline">¿Cancelar cotización?</v-card-title>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" text @click="dialog = false">No</v-btn>
                <v-btn color="green darken-1" text @click="cancel_quotation()">Si</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
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
            ],dialog:false,
            quotation_for_delete:'',
            error: false,message:'',
        }
    },computed:{
        quotations(){ return this.$store.state.quotations;},
    },methods:{
        open_modal_quotation(){
            $('.header-desktop').css('position','unset');
            this.$store.commit('set_modal_quotation',true);
        },show_confirm(id){
            console.log(id);
            this.quotation_for_delete = id;
            this.dialog = true;
        },
        cancel_quotation(){
            axios.post('/ordenes/cancelarCotizacion/'+this.quotation_for_delete).then(response => {
                if(!response.data.success){
                    this.showError(response.data.message);
                }else{
                    this.$store.dispatch('quotations',this.orden.id);
                    this.cerrar_modal_quotation();
                }
            }).catch(error => {
                this.cerrar_modal_quotation();
                this.showError("Hubo un error inesperado, intente más tarde");
            });
        },cerrar_modal_quotation(){
            this.dialog = false;
        },showError(error){
            this.error = true;
            this.message = error;
            setTimeout(() => {
                this.error = false;
            }, 3000);
        }
    }
}
</script>
