<template>
    <div>
        <div v-if="fixerman != null" class="card">
            <div class="card-header">
                <strong class="card-title mb-3">Perfil Técnico del servicio</strong>
            </div>
            <div class="card-body">
                <div class="mx-auto d-block">
                    <img class="rounded-circle mx-auto d-block" :src="fixerman.avatar" alt="Card image cap">
                    <h5 class="text-sm-center mt-2 mb-1">{{ fixerman.name }} {{ fixerman.lastName }}</h5>
                    <div class="location text-sm-center">
                    </div>
                </div>
                <hr>
                <div class="card-text text-sm-center">
                    <i class="fa fa-envelope"></i> {{ fixerman.email }}<br>
                    <i class="fa fa-phone"></i> {{ fixerman.phone }}
                </div>
                <div class="aligncenter">
                    <button type="button" class="btn btn-danger" @click="deleteFixerman()">Eliminar Técnico</button>
                </div>
            </div>
        </div>
        <v-app>
        <div class="card" v-if="fixerman == null">
            <div class="card-header">
                <strong class="card-title mb-3">Técnico sin asignar</strong>
            </div>
            <div class="card-body">
                <div class="mx-auto d-block">
                    <img class="rounded-circle mx-auto d-block" src="https://kliphome.com/img/profile.png" alt="Card image cap">
                </div>
                <hr>
                <div class="card-text text-sm-center">
                    <button class="au-btn au-btn-icon au-btn--green au-btn--small" type="button"  title="Ver"   @click="getFixermanList()">
                        Asignar Técnico
                    </button>
                </div>
            </div>
        </div>
            </v-app>

    </div>
</template>
<style lang='scss'>
    @import '~vuetify/dist/vuetify.min.css';

    #cardContent{
        overflow-y: scroll;
    }
    .v-application--wrap{
        min-height: 50px !important;
    }.aligncenter{
        padding-top:3%;
        text-align: center;
    }
</style>
<script>
export default {

    props:{
        fixerman:Object,
        orden:Object
    },methods:{
        getFixermanList(){
            this.$store.dispatch('getFixermanList');
        },deleteFixerman(){
            axios.post('/tecnicos/eliminarTecnico/'+this.fixerman.id+'/'+this.orden.id).then(response => {
                    if(response.data.success == true){
                        this.fixerman = null;
                    }
                    // dialog.close();
                }).catch(error => {
                    alert("No se ha podido eliminar al Técnico");
                });
        }
    },computed:{
    }
}
</script>
