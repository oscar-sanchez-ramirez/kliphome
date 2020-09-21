<template>
        <v-card>
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
    }
}
</script>

<style>

</style>
