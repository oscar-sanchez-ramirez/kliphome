<template>
  <v-app>
    <v-container fluid style="height: 100%;margin-top:20px;">
       <v-tabs>
        <v-tab href="#tab-1">Detalles</v-tab>
        <v-tab href="#tab-2">Técnico</v-tab>
        <v-tab href="#tab-3">Pagos</v-tab>
        <v-tab href="#tab-4">Cotizaciones</v-tab>
        <v-tab href="#tab-5">Valoraciones</v-tab>
        <v-tab href="#tab-6">Comentarios</v-tab>
        <v-tab href="#tab-7"><i class="fa fa-cog"></i></v-tab>

        <v-tab-item value="tab-1">
            <v-card flat tile>
                <detail-component :orden="orden" :fixerman="fixerman" :extra_info="extra_info"></detail-component>
            </v-card>
        </v-tab-item>
        <v-tab-item value="tab-2">
            <v-card flat tile>
                <v-card-text><fixerman-component :fixerman="fixerman" :orden="orden"></fixerman-component></v-card-text>
            </v-card>
        </v-tab-item>
        <v-tab-item value="tab-3">
            <v-card flat tile>
                <v-card-text><payments-component :payments="payments" :orden="orden"></payments-component></v-card-text>
            </v-card>
        </v-tab-item>
        <v-tab-item value="tab-4">
            <v-card flat tile>
                <v-card-text><quotations-component :orden="orden"></quotations-component></v-card-text>
            </v-card>
        </v-tab-item>
        <v-tab-item value="tab-5">
            <v-card flat tile>
                <v-card-text><qualifies-component :orden="orden"></qualifies-component></v-card-text>
            </v-card>
        </v-tab-item>
        <v-tab-item value="tab-6">
            <v-card flat tile>
                <v-card-text><comments-component :orden="orden"></comments-component></v-card-text>
            </v-card>
        </v-tab-item>
        <v-tab-item value="tab-7">
            <v-card flat tile>
                <v-card-text><configuration-component :orden="orden"></configuration-component></v-card-text>
            </v-card>
        </v-tab-item>
      </v-tabs>
        <v-dialog v-model="modal_list_fixerman" scrollable max-width="700px">
            <v-card>
                <v-card-title>
                <span class="headline">Técnicos</span>
                </v-card-title>
                <v-card-text>
                <v-container>
                    <v-data-table :headers="headers" :items="fixerman_list">
                    <template #item.full_name="{ item }">{{ item.name }} {{ item.lastName }}</template>
                    <template #item.options="{ item }"><v-btn @click="seleccionar(item.id)" color="success">Asignar</v-btn></template>
                    <template #item.categories="{ item }">{{ categories(item.categories) }}</template>
                    </v-data-table>
                </v-container>
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="cerrar_modal_fixerman()">Cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="modal_quotation" scrollable max-width="760px">
            <v-card>
                <v-card-title>
                <span class="headline">Nueva Cotización</span>
                </v-card-title>
                <v-card-text>
                    <v-alert dense border="left" type="error" v-model="error">{{ message }}</v-alert>
                    <v-container>
                        <b>Solución al problema: </b>
                        <editor api-key="mqyeuwcut76aqf31rhioj4aatozqntro0huoliqlj0h6q5qf" v-model="quotation.solution" :init="{height: 150, menubar: false, plugins: ['advlist autolink lists link image charmap print preview anchor','searchreplace visualblocks code fullscreen','insertdatetime media table paste code help wordcount'], toolbar: 'undo redo | formatselect | bold italic backcolor | \ alignleft aligncenter alignright alignjustify | \ bullist numlist outdent indent | removeformat | help'}"/>
                        <b>Materiales necesarios: </b>
                        <editor api-key="mqyeuwcut76aqf31rhioj4aatozqntro0huoliqlj0h6q5qf" v-model="quotation.materials" :init="{height: 150, menubar: false, plugins: ['advlist autolink lists link image charmap print preview anchor','searchreplace visualblocks code fullscreen','insertdatetime media table paste code help wordcount'], toolbar: 'undo redo | formatselect | bold italic backcolor | \ alignleft aligncenter alignright alignjustify | \ bullist numlist outdent indent | removeformat | help'}"/>
                        <v-row>
                            <v-col cols="6">
                                <v-text-field label="Precio por materiales" v-model="quotation.price"></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field label="Precio por mano de obra" v-model="quotation.workforce"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-switch v-model="garantia" label="Garantía"></v-switch><br>
                        <div v-if="mostrar_garantia">
                            <v-row>
                                <v-col cols="6">
                                    <v-text-field label="Cantidad" v-model="quotation.warranty_num" hide-details="auto"></v-text-field>
                                </v-col>
                                <v-col cols="6">
                                    <v-select :items="periodos" label="Periodo" v-model="quotation.warranty_text"></v-select>
                                </v-col>
                            </v-row>
                        </div>
                        <v-row align="center">
                           <v-btn x-large color="success" dark @click="enviar_cotizacion()">Enviar</v-btn>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="cerrar_modal_quotation()">Cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="modal_payment" scrollable max-width="760px">
            <v-card>
                <v-card-title>
                <span class="headline">Nuevo Pago</span>
                </v-card-title>
                <v-card-text>
                    <v-alert dense border="left" type="error" v-model="error">{{ message }}</v-alert>
                    <v-container>
                        <v-row>
                            <v-col cols="6">
                                <v-text-field label="Monto" v-model="payment.price"></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-select v-model="payment.description"
                                :items="conceptos"
                                label="Concepto"
                                ></v-select>
                            </v-col>
                        </v-row>
                        <v-row align="center">
                           <v-btn x-large color="success" dark @click="guardar_pago()">Enviar</v-btn>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="cerrar_modal_payment()">Cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model=modal_comment scrollable max-width="760px">
            <v-card>
            <v-card-title>
                <span class="headline">Nuevo Comentario</span>
            </v-card-title>
            <v-card-text>
                    <v-alert dense border="left" type="error" v-model="error">{{ message }}</v-alert>
                    <v-container>
                        <v-row>
                            <v-col>
                                <v-text-field label="Comentario" v-model="comment.comment"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row align="center">
                           <v-btn x-large color="success" dark @click="guardar_comentario()">Enviar</v-btn>
                        </v-row>
                    </v-container>
                </v-card-text>
        </v-card>
        </v-dialog>
        <v-dialog v-model="modal_qualify" scrollable max-width="760px">
            <v-card>
            <v-card-title>
                <span class="headline">Nueva Valoración</span>
            </v-card-title>
            <v-card-text>
                    <v-alert dense border="left" type="error" v-model="error">{{ message }}</v-alert>
                    <v-container>
                        <v-row>
                            <v-col cols="4">
                                <v-text-field @keypress="onlyNumber" label="Presentación" type="number" :max='5' v-model="qualify.presentation"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field @keypress="onlyNumber" label="Puntualidad" type="number" :max='5' v-model="qualify.puntuality"></v-text-field>
                            </v-col>
                            <v-col cols="4">
                                <v-text-field @keypress="onlyNumber" label="Solución al problema" type="number" :max='5' v-model="qualify.problemSolve"></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field label="Comentario" v-model="qualify.comment"></v-text-field>
                            </v-col>
                        </v-row>
                        <v-row align="center">
                           <v-btn x-large color="success" dark @click="guardar_valoracion()">Enviar</v-btn>
                        </v-row>
                    </v-container>
                </v-card-text>
        </v-card>
        </v-dialog>
      </v-container>
  </v-app>

</template>

<style lang='scss'>
    @import '~vuetify/dist/vuetify.min.css';
</style>
<script>
import Editor from '@tinymce/tinymce-vue'

export default{
    name: 'app',
    components: {
      editor: Editor
    },
  props:{
    orden: Object,
    fixerman:Object,
    payments:Array,
    extra_info:Object
  },mounted(){console.log(this.orden);},
  data: () => ({
      headers: [{text: 'Nombres',value:'full_name'},{text: 'Telefono',value:'phone'},{text:'Categorias',value:'categories'},{text:'Asignar',value:'options'}],
      quotation:{price:'',workforce:'',solution:'',materials:'',warranty_text:'',warranty_num:''},
      payment:{description:'',state:1,price:'',code_payment:'EFECTIVO'},
      comment:{comment:''},
      qualify:{user_id:'',selected_order_id:'',presentation:'',puntuality:'',problemSolve:'',comment:'',tip:0},
      error: false,
      garantia:false,
      mostrar_garantia:false,
      periodos: ['dias', 'semanas', 'meses'],
      conceptos: ['VISITA','PAGO POR SERVICIO','PROPINA'],
      message:'',
    }),computed:{
        modal_list_fixerman: {
            get () {
             return this.$store.state.modal_list_fixerman;
            },
            set (value) {
               this.$store.commit('set_modal_list_fixerman',value);
            }
        },modal_quotation:{
            get () {
             return this.$store.state.modal_quotation;
            },
            set (value) {
                $('.header-desktop').css('position','fixed');
               this.$store.commit('set_modal_quotation',value);
            }
        },
        modal_payment:{
            get () {
             return this.$store.state.modal_payment;
            },
            set (value) {
                $('.header-desktop').css('position','fixed');
               this.$store.commit('set_modal_payment',value);
            }
        },
        modal_comment:{
            get () {
             return this.$store.state.modal_comment;
            },
            set (value) {
                $('.header-desktop').css('position','fixed');
               this.$store.commit('set_modal_comment',value);
            }
        },
        modal_qualify:{
            get () {
             return this.$store.state.modal_qualify;
            },
            set (value) {
                $('.header-desktop').css('position','fixed');
               this.$store.commit('set_modal_qualify',value);
            }
        },
        fixerman_list(){ return this.$store.state.fixerman_list;}
    },methods:{
        cerrar_modal_fixerman(){
            this.$store.commit('set_modal_list_fixerman',false);
        },cerrar_modal_comment(){
            this.$store.commit('set_modal_comment',false);
        },cerrar_modal_quotation(){
            $('.header-desktop').css('position','fixed');
            this.$store.commit('set_modal_quotation',false);
        },cerrar_modal_payment(){
            $('.header-desktop').css('position','fixed');
            this.$store.commit('set_modal_payment',false);
        },cerrar_modal_qualify(){
            $('.header-desktop').css('position','fixed');
            this.$store.commit('set_modal_qualify',false);
        },seleccionar(id){
            axios.post('/tecnicos/asignarTecnico/'+id+'/'+this.orden.id).then(response => {
                    window.location.href = window.location.origin+"/ordenes/detalle-orden/"+this.orden.id;
            }).catch(error => {});
        },categories(cats){
            let categories = "";
            let categories_f = this.$store.state.categories_list;
            for (let indexJ = 0; indexJ < cats.length; indexJ++) {
                    let category_id = cats[indexJ].category_id;
                    const category = categories_f.find( x => x.id === category_id );
                    categories = categories+' '+category.title;
                }
            return categories;
        },guardar_pago(){
                let formData = new FormData();
                formData.append('description',this.payment.description);
                formData.append('state',this.payment.state);
                formData.append('price',this.payment.price);
                formData.append('code_payment',this.payment.code_payment);
                axios.post('/ordenes/nuevo-pago/'+this.orden.id,formData).then(response => {
                    if(!response.data.success){
                        this.showError("Hubo un error, intente más tarde");
                    }else{
                        this.$store.dispatch('payments',this.orden.id);
                        this.cerrar_modal_payment();
                    }
                }).catch(error => {
                  this.showError("Hubo un error inesperado, intente más tarde");
                });
        },guardar_comentario(){
            let formData = new FormData();
            formData.append('comment',this.comment.comment);
            formData.append('order_id',this.orden.id);
            axios.post('/ordenes/comments',formData).then(response =>{
                    if(!response.data.success){
                        //this.showError("Hubo un error, intente más tarde");
                    }else{
                        this.$store.dispatch('comments',this.orden.id);
                        this.cerrar_modal_comment();
                    }
            });
        },guardar_valoracion(){
            if(this.orden.fixerman_user != null){
                if(this.qualify.problemSolve > 5 || this.qualify.presentation > 5 || this.qualify.puntuality > 5){
                    alert("La valoración no debe ser mayor a 5");
                    return;
                }
                if(this.qualify.problemSolve == '' || this.qualify.presentation == '' || this.qualify.puntuality == ''){
                    alert("Debe llenar todos los campos");
                    return;
                }
                let formData = new FormData();
                formData.append('user_id',this.orden.fixerman_user.user_id);
                formData.append('selected_order_id',this.orden.fixerman_user.id);
                formData.append('presentation',this.qualify.presentation);
                formData.append('puntuality',this.qualify.puntuality);
                formData.append('problemSolve',this.qualify.problemSolve);
                formData.append('comment',this.qualify.comment);
                axios.post('/ordenes/qualify',formData).then(response =>{
                    if(response.data.success){
                        this.$store.dispatch('qualifies',this.orden.id);
                        this.cerrar_modal_qualify();
                    }else{
                        alert(response.data.message);
                    }
                });
            }else{
                alert("No se asigno un técnico");
            }
        },
        enviar_cotizacion(){
            let check = this.validate_quotation();
            if(check){
                let formData = new FormData();
                formData.append('price',this.quotation.price);
                formData.append('workforce',this.quotation.workforce);
                formData.append('solution',this.quotation.solution);
                formData.append('materials',this.quotation.materials);
                formData.append('warranty_num',this.quotation.warranty_num);
                formData.append('warranty_text',this.quotation.warranty_text);
                axios.post('/ordenes/enviarCotizacion/'+this.orden.id,formData).then(response => {
                    if(!response.data.success){
                        this.showError(response.data.message);
                    }else{
                        this.$store.dispatch('quotations',this.orden.id);
                        this.cerrar_modal_quotation();
                    }
                }).catch(error => {
                  this.showError("Hubo un error inesperado, intente más tarde");
                });
            }else{
                this.showError("Verifique los datos ingresados");
            }

        },validate_quotation(){
            if(this.quotation.solution != "" && this.quotation.materials != "" && this.isNumeric(this.quotation.price) && this.isNumeric(this.quotation.workforce)){
                if(this.mostrar_garantia == true){
                    if(this.isNumeric(this.quotation.warranty_num) && this.quotation.warranty_text != ""){
                        return true;
                    }else{
                        return false;
                    }
                }
                return true;
            }else{
                return false;
            }
        },isNumeric: function (n) {
            return !isNaN(parseFloat(n)) && isFinite(n);
        },showError(error){
            this.error = true;
            this.message = error;
            setTimeout(() => {
                this.error = false;
            }, 3000);
        },onlyNumber ($event) {
   //console.log($event.keyCode); //keyCodes value
   let keyCode = ($event.keyCode ? $event.keyCode : $event.which);
   if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) { // 46 is dot
      $event.preventDefault();
   }
}
    },watch:{
        garantia(val){
            if(val == true){
                this.mostrar_garantia = true;
            }else{
                this.mostrar_garantia = false;
            }
        }
    }
}
</script>
