<template>
    <v-app>
        <div class="container">
            <v-text-field v-model="name" :counter="100" label="Cliente" required @input="name.$touch()"></v-text-field>
            <ul v-if="clientes.length > 0 && name">
                <li v-for="result in clientes.slice(0,10)" :key="result.id" @click="obtener_usuario( result.id,(result.name+' '+result.lastName),result )">
                    <p >{{ result.name+' '+result.lastName }}</p>
                </li>
            </ul>
            <v-row>
                <v-col cols="8">
                    <v-select :items="addresses" v-model="address" label="Dirección" item-text='alias' item-value='id'></v-select>
                </v-col>
                <v-col cols="4">
                    <div >
                        <button class="au-btn au-btn-icon au-btn--green au-btn--small" type="button"  title="Ver"   @click="modalnewaddress()">
                            Crear dirección nueva
                        </button>
                    </div>
                </v-col>
            </v-row>
            <v-select :items="categories" v-model="category" label="Categoria" item-text='title' item-value='id'></v-select>
            <v-row>
                <v-col>
                    <v-menu ref="inicio" v-model="inicio" :close-on-content-click="false" :return-value.sync="date" transition="scale-transition" offset-y min-width="290px">
                        <template v-slot:activator="{ on }">
                        <v-text-field v-model="computedDateFormatted" label="Inicio" readonly v-on="on"></v-text-field>
                        </template>
                        <v-date-picker v-model="date" no-title scrollable>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="inicio = false">Cancel</v-btn>
                        <v-btn text color="primary" @click="$refs.inicio.save(date)">OK</v-btn>
                        </v-date-picker>
                    </v-menu>
                </v-col>
                <v-col>
                    <v-menu
                        ref="menu"
                        v-model="menu2"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        :return-value.sync="time"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                            v-model="time"
                            label="Picker in menu"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                        ></v-text-field>
                        </template>
                        <v-time-picker
                        v-if="menu2"
                        v-model="time"
                        full-width
                        @click:minute="$refs.menu.save(time)"
                        ></v-time-picker>
                    </v-menu>
                </v-col>
            </v-row>
            <v-textarea name="input-7-1" v-model="description" label="Descripción" placeholder="Brindanos una descripción lo más detallada posible"></v-textarea>
            <v-btn small color="primary" @click="submit()">Crear Orden</v-btn>
        </div>
        <v-dialog v-model="modal_new_address" width="500">
            <v-card>
                <v-tabs background-color="transparent" color="basil" grow>
                    <v-tab href="#lista">Direcciones</v-tab>
                    <v-tab href="#nueva_direccion">Nueva Dirección</v-tab>
                    <v-tab-item value="lista">
                        <v-card flat tile>
                            <v-list-item v-for="add in user_addresses" :key="add.id">
                                <v-list-item-content>
                                    <v-list-item-title>{{ add.alias }} - {{ add.street }} &nbsp;&nbsp;<v-btn color="primary" small @click="asignar_direccion(add.id)">Asignar</v-btn></v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                            <h2 v-if="user_addresses.length == 0" id="pt-10">Este usuario no tiene direcciones</h2>
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
<style scoped>
.container{
    padding:10%
}.v-menu__content{
    top:260px !important;
    left: 650px !important;
}#pd-15{
    padding: 5% !important;
}
</style>
<script>
import Vue from 'vue';
import Vuelidate from 'vuelidate';

Vue.use(Vuelidate);
import moment from 'moment';
export default {
    props:{
        categories:Array
    },
    mounted(){
    },
    data: () => ({
      user_id:'',
      category:'',
      name: '',
      description: '',
      clientes:[],
      user: [],
      user_addresses:[],
      date: new Date().toISOString().substr(0, 10),
      dateorder: '',
      address:'',
      menu:false,
      menu2:false,
      inicio: false,
      modal_new_address:false,
      time:null,
      new_address:false,
      addresses:[],
      new_address_field : {alias:'',street:'',reference:'',postal_code:'',colonia:'',municipio:'',exterior:'',interior:'',user_id:''}
    }),
    computed: {
        computedDateFormatted () { return this.formatDate(this.date)},
    },

    methods: {
      submit () {
        if(this.name == "" || this.user_id == ""){
            alert('Debes seleccionar a un cliente');
            return;
        }
        if(this.category == ""){
            alert('Debes seleccionar a una categoría');
            return;
        }
        if(this.time == null){
            alert('Debes seleccionar la hora del servicio');
            return;
        }
        if(this.description == ''){
            alert('Debes ingresar una descripción');
            return;
        }
        if(this.address == ''){
            alert('Debes seleccionar una dirección');
            return;
        }
         const params = {
            user_id: this.user_id,
            address: this.address,
            selected_id:this.category,
            type_service: 'Category',
            service_date: moment(String(this.date+' '+this.time)).format('YYYY/MM/DD hh:mm'),
            service_description: this.description,
            visit_price:'quotation',
        };
        axios.post('store',params).then((response) => {
          if(response.data.success){
            window.location.href = window.location.origin+"/ordenes/detalle-orden/"+response.data.order.id;
          }else{
              alert('Hubo un error al crear la orden, intente mas tárde')
          }
        });
      },formatDate (date) {
        if (!date) return null
        const [year, month, day] = date.split('-')
        return `${day}/${month}/${year}`
      },obtener_usuario(id,name,user){
        this.addresses = [];
        for (let index = 0; index < user.address.length; index++) {
            this.addresses.push(user.address[index]);
            this.user_addresses.push(user.address[index]);
        }
        if(this.addresses.length == 0){
            this.new_address = true;
        }
        this.user_id = id;
        this.name = name;
      }, searchMembers() {
            axios.get('/clientes?query='+this.name)
            .then(response => this.clientes = response.data)
            .catch(error => {});
        },cerrar_modal_address(){
            $('.header-desktop').css('position','flex');
            this.modal_new_address = false;
        },modalnewaddress(){
            $('.header-desktop').css('position','unset');
            this.modal_new_address = true;
        },save_address(){
            this.new_address_field.user_id = this.user_id;
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
                this.addresses.push(...response.data.address);
                this.modal_new_address = false;
                this.new_address = false;
                cerrar_modal_address();
            })
            .catch(error => {});
        }
    },
    watch: {
        name(after, before) {
            this.searchMembers();
        }
    },
}
</script>
