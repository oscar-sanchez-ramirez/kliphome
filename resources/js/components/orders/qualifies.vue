<template>
        <v-card>
            <v-row>
                <v-btn depressed small color="primary" @click="open_modal_qualify()">Nueva</v-btn>
            </v-row>
            <v-container>

                <v-data-table :headers="headers" :items="qualifies">

                </v-data-table>
            </v-container>
        </v-card>
</template>

<script>
export default {
 props:{
        orden:Object
    },mounted(){
        this.$store.dispatch('qualifies',this.orden.id);
    },data(){
        return{
            headers: [
                {text: 'Fecha',value:'created_at'},{text: 'Presentación',value:'presentation'},{text:'Solución',value:'problemSolve'},{text:'Puntualidad',value:'puntuality'}
            ],
            dialog_confirm:false,
            quotation_id:'',
            error: false,message:'',
            confirm:[]
        }
    },computed:{
        qualifies(){ return this.$store.state.qualifies;},
    },methods:{
        open_modal_qualify(){
            $('.header-desktop').css('position','unset');
            this.$store.commit('set_modal_qualify',true);
        }
    }
}
</script>
