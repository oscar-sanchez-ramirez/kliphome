<template>
    <v-app>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title mb-3">Perfil del usuario</strong>
                    </div>
                    <div class="card-body">
                        <div class="mx-auto d-block">
                            <img class="rounded-circle mx-auto d-block" :src="user.avatar" alt="Card image cap">
                            <h5 class="text-sm-center mt-2 mb-1">{{ user.name }} {{ user.lastName }}</h5>
                            <div class="location text-sm-center">
                                <i class="fa fa-map-marker"></i> {{ address.alias}}, {{ address.street }}
                                 <p>Municipio: {{address.municipio }}</p>
                                <p>Ext: {{ address.exterior }}</p>
                                <p>Int: {{ address.interior }}</p>
                                <p>Cód Postal: {{ address.postal_code }}</p>
                                <p>Colonia: {{ address.colonia }}</p>
                                <p>Referencia: {{ address.reference }}</p>
                                <div v-if="fixerman">
                                    <div v-show="orden.state != 'PENDING' && orden.state != 'FIXERMAN_NOTIFIED'">
                                        <h4 v-show="orden.fixerman_arrive">Técnico aun no llego al punto</h4>
                                    </div>
                                </div>
                                <div v-if="!fixerman">
                                    <h4>Esperando confirmación de algún técnico</h4>
                                </div>
                                <div v-if="orden.pre_coupon != null">
                                    <b><i class="fas fa-ticket-alt"></i>Cupón Activo de {{ orderCoupon(orden.pre_coupon) }}% ({{ orden.pre_coupon }})</b><br>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="card-text text-sm-center">
                            <i class="fa fa-envelope"></i> {{ user.email }}<br>
                            <i class="fa fa-phone"></i> {{ user.phone }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <img class="card-img-top" type="button" :src="orden.service_image" alt="Card image cap" >
                    <div class="card-body">
                        <h4 class="card-title mb-3">{{ parseDate(orden.service_date) }} / {{ service }} /
                                <span class="badge badge-danger" v-if="orden.state == 'PENDING'">PENDIENTE DE COTIZACIÓN</span>
                                <span class="badge badge-info" v-if="orden.state == 'FIXERMAN_NOTIFIED'">TÉCNICOS NOTIFICADOS</span>
                                <span class="badge badge-info" v-if="orden.state == 'ACCEPTED' || orden.state == 'FIXERMAN_APPROVED'">TÉCNICO ACEPTÓ SOLICITUD</span>
                                <span class="badge badge-danger" v-if="orden.state == 'CANCELLED'">CANCELADO</span>
                                <span class="badge badge-success" v-if="orden.state == 'DONE'">TERMINADO</span>
                                <span class="badge badge-success" v-if="orden.state == 'QUALIFIED'">CALIFICADO</span>
                        </h4>
                        <p class="card-text">
                            {{ orden.service_description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </v-app>
</template>

<style lang='scss'>
    @import '~vuetify/dist/vuetify.min.css';

    #cardContent{
        overflow-y: scroll;
    }
    .v-application--wrap{
        min-height: 50px !important;
    }
</style>
<script>
export default {
    props:{
        orden:Object,
        fixerman:Object,
        // service:Object
    },mounted(){
        this.$store.dispatch('user_detail',{user_id:this.orden.user_id,address:this.orden.address});
        this.$store.dispatch('getService',{type:this.orden.type_service,id:this.orden.selected_id});
    },computed:{
        user(){ return this.$store.state.user;},
        address(){ return this.$store.state.address;},
        service(){return this.$store.state.service;}
    },methods:{
        orderCoupon(coupon){
            axios.get('/ordenes/cupon/'+coupon).then(response => {
              return response.data.coupon.discount
            });
        },parseDate(date){
            return moment(date).format('Do,MMM H:mm');
        }
    }
}
</script>
