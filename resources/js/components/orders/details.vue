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
                                <div v-if="address != null">
                                    <i class="fa fa-map-marker"></i> {{ address.alias}}, {{ address.street }}
                                     <p>Municipio: {{address.municipio }}</p>
                                    <p>Ext: {{ address.exterior }}</p>
                                    <p>Int: {{ address.interior }}</p>
                                    <p>Cód Postal: {{ address.postal_code }}</p>
                                    <p>Colonia: {{ address.colonia }}</p>
                                    <p>Referencia: {{ address.reference }}</p>
                                </div>
                                <div v-if="address == null || address == ''">
                                    <button class="au-btn au-btn-icon au-btn--green au-btn--small" type="button"  title="Ver"   @click="getAddList()">
                                        Asignar Dirección
                                    </button>
                                </div>
                                <div v-if="fixerman">
                                    <div v-show="orden.state != 'PENDING' && orden.state != 'FIXERMAN_NOTIFIED'">
                                        <h4 v-show="orden.fixerman_arrive == 'NO'">Técnico aún no llegó al punto</h4>
                                        <h4 v-show="orden.fixerman_arrive == 'SI'">Técnico indicó llegada</h4>
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
                    <img v-if="orden.gallery.length == 0" class="card-img-top" type="button" :src="orden.service_image" alt="Card image cap" >
                    <div v-if="orden.gallery.length > 0">
                        <v-carousel height="500" hide-delimiter-background show-arrows-on-hover>
                            <v-carousel-item v-for="(slide, i) in orden.gallery" :key="i">
                                <img :src="orden.gallery[i].image" alt=""  class="img-responsive" width="100%" height="100%" @click="expand(orden.gallery[i].image)">
                            </v-carousel-item>
                        </v-carousel>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title mb-3">{{ parseDate(orden.service_date) }} / {{ service }} /
                                <span class="badge badge-danger" v-if="orden.state == 'PENDING'">PENDIENTE DE COTIZACIÓN</span>
                                <span class="badge badge-info" v-if="orden.state == 'FIXERMAN_NOTIFIED'">TÉCNICOS NOTIFICADOS</span>
                                <span class="badge badge-info" v-if="orden.state == 'ACCEPTED' || orden.state == 'FIXERMAN_APPROVED'">TÉCNICO ACEPTÓ SOLICITUD</span>
                                <span class="badge badge-danger" v-if="orden.state == 'CANCELLED'">CANCELADO</span>
                                <span class="badge badge-success" v-if="orden.state == 'DONE' || orden.state == 'FIXERMAN_DONE'">TERMINADO</span>
                                <span class="badge badge-success" v-if="orden.state == 'QUALIFIED'">CALIFICADO</span>
                        </h4>
                        <p class="card-text">
                            {{ orden.service_description }}
                        </p>
                        <div v-if="extra_info != null">
                            <p><b>Tipo de plaga: </b>{{ extra_info.tipo_plaga }}</p>
                            <p><b># Pisos: </b>{{ extra_info.pisos }}</p>
                            <p><b># Metros cuadrados: </b>{{ extra_info.metros }}</p>
                            <p><b># Cuartos: </b>{{ extra_info.cuartos }}</p>
                            <p><b>Jardín: </b><span v-if="extra_info.jardin == 'false'">NO</span><span v-else>SI</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
            <v-toolbar dark color="primary">
            <v-btn icon dark @click="close_modal()">
                Cerrar
            </v-btn>
            </v-toolbar>
            <img :src="url" alt="" class="img-responsive" width="100%" height="100%">
        </v-dialog>
        <v-dialog v-model="dialog_address" scrollable width="500" height="300">
            <v-card>
                <v-tabs background-color="transparent" color="basil" grow>
                    <v-tab href="#lista">Direcciones</v-tab>
                    <v-tab href="#nueva_direccion">Nueva Dirección</v-tab>

                    <v-tab-item value="lista">
                        <v-card flat tile>
                            <v-list-item v-for="add in user.address" :key="add.id">
                                <v-list-item-content>
                                    <v-list-item-title>{{ add.alias }} - {{ add.street }} &nbsp;&nbsp;<v-btn color="primary" small @click="asignar_direccion(add.id)">Asignar</v-btn></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <h2 v-if="user.address.length == 0" id="pt-10">Este usuario no tiene direcciones</h2>
                        </v-card>
                    </v-tab-item>
                    <v-tab-item value="nueva_direccion">
                        <v-card flat tile>
                            <v-card-title class="headline grey lighten-2">
                                Nueva dirección
                            </v-card-title>
                            <div id="pd-15">
                                <v-text-field v-model="new_address_field.alias" label="Alias"></v-text-field>
                                <v-text-field v-model="new_address_field.street" label="Dirección"></v-text-field>
                                <v-text-field v-model="new_address_field.reference" label="Referencia"></v-text-field>
                                <v-text-field v-model="new_address_field.postal_code" label="Código postal"></v-text-field>
                                <v-text-field v-model="new_address_field.colonia" label="Colonia"></v-text-field>
                                <v-text-field v-model="new_address_field.municipio" label="Municipio"></v-text-field>
                                <v-text-field v-model="new_address_field.exterior" label="Num Exterior"></v-text-field>
                                <v-text-field v-model="new_address_field.interior" label="Num Interior"></v-text-field>
                                <v-btn  color="primary" @click="save_address">Guardar</v-btn>
                            </div>
                        </v-card>
                    </v-tab-item>
                </v-tabs>
            </v-card>
        </v-dialog>
    </v-app>
</template>

<style lang='scss'>
    @import '~vuetify/dist/vuetify.min.css';

    #cardContent{
        overflow-y: scroll;
    }#pd-15{
        padding: 3% !important;
    }#pt-10{
        text-align: center;
        padding: 4% !important;
    }
    .v-application--wrap{
        min-height: 50px !important;
    }.card-img-top{
        min-height: 300px;
    }
</style>
<script>
import moment from 'moment'
export default {
    props:{
        orden:Object,
        fixerman:Object,
        extra_info:Object
    },mounted(){
        this.$store.dispatch('user_detail',{user_id:this.orden.user_id,address:this.orden.address});
        this.$store.dispatch('getService',{type:this.orden.type_service,id:this.orden.selected_id});
    },computed:{
        user(){ return this.$store.state.user;},
        address(){ return this.$store.state.address;},
        service(){return this.$store.state.service;},
        user_address(){return this.$store.state.user_address}
    },methods:{
        orderCoupon(coupon){
            axios.get('/ordenes/cupon/'+coupon).then(response => {
              return response.data.coupon.discount
            });
        },parseDate(date){
            //return moment(date).format('D,MMM H:mm');
            return moment(String(date)).format('D,MMM H:mm')
        },expand(url){
            $('.header-desktop').css('position','unset');
            this.url = url;
            this.dialog = true;
        },close_modal(){
            $('.header-desktop').css('position','fixed');
            this.dialog = false;
        },getAddList(){
            $('.header-desktop').css('position','unset');
            this.dialog_address = true;
        },close_modal_address(){
            this.dialog_address = false;
        },save_address(){
            this.new_address_field.user_id = this.orden.user_id;

            if(this.new_address_field.alias == '' || this.new_address_field.street == '' || this.new_address_field.reference == ''){
                alert("Debes llenar todos los datos");
                return;
            }
            let formData = new FormData();
            formData.append('alias',this.new_address_field.alias);
            formData.append('street',this.new_address_field.street);
            formData.append('reference',this.new_address_field.reference);
            formData.append('postal_code',this.new_address_field.postal_code);
            formData.append('colonia',this.new_address_field.colonia);
            formData.append('municipio',this.new_address_field.municipio);
            formData.append('exterior',this.new_address_field.exterior);
            formData.append('interior',this.new_address_field.interior);
            formData.append('user_id',this.new_address_field.user_id);

             axios.post('/clientes/nueva_direccion',formData)
            .then(response => {
                alert("Se guardó la dirección");
                // this.$store.state.address = ;
                setTimeout(() => {
                    this.asignar_direccion(response.data.address[response.data.address.length-1].id);
                }, 500);
            }).catch(error => {});
        },asignar_direccion(id){
            axios.post('/ordenes/asignar_direccion/'+id+'/'+this.orden.id).then(response => {
            //   return response.data.coupon.discount
                if(response.data.success){
                    console.log(response.data.address);
                    this.$store.state.address = response.data.address;
                    $('.header-desktop').css('position','fixed');
                    this.dialog_address = false;
                }
            });
        }
    },data () {
      return {
        dialog: false,
        dialog_address:false,
        new_address_field : {alias:'',street:'',reference:'',postal_code:'',colonia:'',municipio:'',exterior:'',interior:'',user_id:''},
        url:''
      }
    },
}
</script>
