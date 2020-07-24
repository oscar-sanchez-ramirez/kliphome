<template>
    <v-app>
        <div class="container">
            <v-text-field
              v-model="name"
              :counter="100"
              label="Cliente"
              required
              @input="$v.name.$touch()"
              @blur="$v.name.$touch()"
            ></v-text-field>
            <ul v-if="clientes.length > 0 && name">
                <li v-for="result in clientes.slice(0,10)" :key="result.id" @click="obtener_usuario( result.id,(result.name+' '+result.lastName) )">
                    <p >{{ result.name+' '+result.lastName }}</p>
                </li>
            </ul>
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

    </v-app>
</template>
<style scoped>
.container{
    padding:10%
}.v-menu__content{
    top:260px !important;
    left: 650px !important;
}
</style>
<script>
export default {
    props:{
        categories:Array
    },
    mounted(){
        console.log(this.categories);
    },

    data: () => ({
      user_id:'',
      category:'',
      name: '',
      description: '',
      clientes:[],
      date: new Date().toISOString().substr(0, 10),
      dateorder: '',
      menu:false,
      menu2:false,
      inicio: false,
      time:null
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
        if(this.time == ""){
            alert('Debes seleccionar la hora del servicio');
            return;
        }
        if(this.description == ""){
            alert('Debes ingresar una descripción');
            return;
        }
         const params = {
            user_id: this.user_id,
            selected_id:this.category,
            type_service: 'Category',
            service_date: this.date+' '+this.time,
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
      },
      formatDate (date) {
        if (!date) return null
        const [year, month, day] = date.split('-')
        return `${day}-${month}-${year}`
      },
      obtener_usuario(id,name){
          this.user_id = id;
          this.name = name;
      },
      searchMembers() {
            axios.get('/clientes?query='+this.name)
            .then(response => this.clientes = response.data)
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
