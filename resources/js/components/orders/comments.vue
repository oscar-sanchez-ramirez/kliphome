<template>
    <v-card>
        <v-row>
            <v-btn depressed small color="primary" @click="open_modal_comment()">Nueva</v-btn>
        </v-row>
        <v-card-text>
            <v-container>
                <v-data-table :headers="headers" :items="comments"></v-data-table>
            </v-container>
        </v-card-text>
        </v-card>
</template>
<script>
export default {
    props:{
        orden:Object
    },mounted(){
        this.$store.dispatch('comments',this.orden.id);
    },computed:{
        comments(){ return this.$store.state.comments;},
    },data(){
        return{
            headers: [
                {text: 'Comentario',value:'comment'},{text: 'Fecha',value:'created_at'}
            ],
        }
    },methods:{
        open_modal_comment(){
            $('.header-desktop').css('position','unset');
            this.$store.commit('set_modal_comment',true);
        }
    }
}
</script>
