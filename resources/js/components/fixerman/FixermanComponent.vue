<template>
  <v-app>
      <v-container fluid style="height: 100%;margin-top:20px;">
          <h5>Técnico : {{ fixerman.name }} {{ fixerman.lastName }}</h5>
       <v-tabs>
        <v-tab href="#tab-1">Ordenes</v-tab>
        <v-tab href="#tab-2">Ficha</v-tab>
        <v-tab href="#tab-3">Categorías</v-tab>
        <v-tab href="#tab-4">Calificaciones</v-tab>
        <v-tab href="#tab-5">Pagos</v-tab>
        <v-tab href="#tab-6">Datos</v-tab>
        <v-tab href="#tab-7">Imagenes</v-tab>
        <v-tab-item value="tab-1">
            <v-card flat tile>
                <calendar-component :fixerman="fixerman"></calendar-component>
            </v-card>
        </v-tab-item>
        <v-tab-item value="tab-2">
            <v-card flat tile>
                <v-card-text><stats-component :ficha="ficha" :fixerman="fixerman"></stats-component></v-card-text>
            </v-card>
        </v-tab-item>
        <v-tab-item value="tab-3">
            <v-card flat tile>
                <v-card-text><categories-component :categories="categories" :delegation="delegation"></categories-component></v-card-text>
            </v-card>
        </v-tab-item>
        <v-tab-item value="tab-4">
            <v-card flat tile>
                <v-card-text><reviews-component :categories="categories" :delegation="delegation" :fixerman="fixerman"></reviews-component></v-card-text>
            </v-card>
        </v-tab-item>
        <v-tab-item value="tab-5">
            <v-card flat tile>
                <v-card-text><payments-component :fixerman="fixerman"></payments-component></v-card-text>
            </v-card>
        </v-tab-item>
         <v-tab-item value="tab-6">
            <v-card flat tile>
                <v-card-text><info-component :fixerman="fixerman"></info-component></v-card-text>
            </v-card>
        </v-tab-item>
        <v-tab-item value="tab-7">
            <v-card flat tile>
                <v-card-text><images-component :fixerman="fixerman"></images-component></v-card-text>
            </v-card>
        </v-tab-item>
      </v-tabs>
          <v-dialog v-model="modal_image" scrollable max-width="760px">
            <v-card>
                <v-card-title class="headline grey lighten-2">
                {{ text_transform(item.type) }}
                </v-card-title>

                <v-row>
                    <v-col cols="2"></v-col>
                     <v-col cols="4">
                        <v-img
                        :src="item.path" lazy-src="https://picsum.photos/id/11/100/60" aspect-ratio="1" max-width="150" max-height="150">
                        <template v-slot:placeholder>
                            <v-row align="center" justify="center">
                                <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                            </v-row>
                        </template>
                    </v-img>
                    </v-col>
                    <v-col cols="4">

                         <input type="file" id="file" ref="file"  placeholder="Selecciona una imagen" accept="image/png, image/jpeg, image/bmp" v-on:change="onChangeFileUpload()"/>
                        <v-btn color="primary" @click="actualizar_imagen()">Actualizar</v-btn>
                    </v-col>
                    <v-col cols="2"></v-col>
                </v-row>

                <v-divider></v-divider>

                <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" text @click="cerrar_modal_imagen()">
                    Cerrar
                </v-btn>
                </v-card-actions>
            </v-card>
            </v-dialog>
      </v-container>
  </v-app>
</template>
<style lang='scss'>
    @import '~vuetify/dist/vuetify.min.css';
</style>
<script>
export default{
  props:{
    fixerman: Object,
    ficha:Object,
    categories:Array,
    delegation:Array,
    file:undefined
  },
  methods:
  {
      cerrar_modal_imagen(){
        $('.header-desktop').css('position','fixed');
        this.$store.commit('set_modal_images',false);
      },
    text_transform(text){
        switch (text) {
            case 'identificacion_oficial':
                return 'Identificación Oficial:'
            case 'comprobante_domicilio':
                return 'Comprobante de Domicilio:'
            default:
                return 'Prueba Psicométrica:'
        }
    },
    onChangeFileUpload() {
      this.file = this.$refs.file.files[0];
    },
    actualizar_imagen(){
        if(this.file != undefined){
            let formData = new FormData();
            formData.append('imagen', this.file);
            formData.append('tipo',this.item.type);
            axios.post(
                "/tecnicos/guardar_imagen_dato/"+this.fixerman.id
                ,formData
                ,{headers: {"Content-Type": "multipart/form-data"}}
            )
            .then(response => {
                if(response.data.success){
                    $('.header-desktop').css('position','fixed');
                    this.$store.commit('set_modal_images',false);
                    this.fixerman = response.data.fixerman
                }
            })
            .catch(e => {
            //...
            });
        }else{
            alert('Secciona una imagen');
        }
    }
  },
  computed:{
      modal_image:{
         get () {
            return this.$store.state.modal_image;
        },
        set (value) {
            $('.header-desktop').css('position','fixed');
            this.$store.commit('set_modal_images',value);
        }
    },item(){ return this.$store.state.item;}
  }
}
</script>
