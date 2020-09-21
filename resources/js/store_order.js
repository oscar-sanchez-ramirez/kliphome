import Vue from 'vue'
import Vuex from 'vuex'
import moment from 'moment'

Vue.use(Vuex);
export default new Vuex.Store({
    state: {
        // chat
        dialog: false,
        // orders
        user:[],
        address:[],
        service:'',
        modal_list_fixerman:false,
        modal_quotation:false,
        modal_payment:false,
        fixerman_list:[],
        categories_list:[],
        quotations:[],
        payments:[],
        qualifies:[]
    },
    mutations: {
        // Chat
        setDialog(state, value) {
            state.dialog = value;
        },
        // order
        setUser(state,user){
            state.user = user;
        },setAddress(state,address){
            state.address = address;
        },setService(state,service){
            state.service = service;
        },set_modal_list_fixerman(state,val){
            state.modal_list_fixerman = val;
        },set_fixerman_list(state,val){
            state.fixerman_list = val;
        },set_categories_list(state,val){
            state.categories_list = val;
        },setQuotations(state,val){
            state.quotations = val;
        },set_modal_quotation(state,val){
            state.modal_quotation = val;
        },set_modal_payment(state,val){
            state.modal_payment = val;
        },setPayments(state,val){
            state.payments = val;
        },setQualifies(state,val){
            state.qualifies = val;
        }

    },
    actions: {
      // Orders
      user_detail(context,object){
        axios.get('/ordenes/detalle_usuario/'+object.user_id+'/'+object.address).then(response=>{
            context.commit('setUser',response.data.user);
            context.commit('setAddress',response.data.address);
        });
      },
    getService(context,object){
        axios.get('/ordenes/getService/'+object.type+'/'+object.id).then(response => {
            context.commit("setService", response.data);
        });
    },
    orders(context,tecnico){
    axios.get('/tecnicos/ordenes_tecnico/'+tecnico.id).then(
        response=>{
            let orders = response.data;
            let events = [];
            for (let index = 0; index < orders.length; index++) {
                let color;
                if(orders[index]["state"] == "QUALIFIED"){
                    color = 'green';
                }else if(orders[index]["state"] == "CANCELLED"){
                color = "red";
                }else{
                    color = "blue";
                }
                events[index] =
                {
                    'title' : "Servicio con "+orders[index]["name"]+" "+orders[index]["lastName"],
                    'start' : moment(String(orders[index]["service_date"])).format('YYYY-MM-DD'),
                    'textColor': 'white',
                    'color':color,
                    'children':orders[index],
                    'extendedColor':color
                }

            }
            context.commit('orderList',events);
        }
        );
    },
    getFixermanList(context){
        axios.get('/tecnicos/listado').then(response => {
            context.commit('set_fixerman_list',response.data.fixerman);
            context.commit('set_categories_list',response.data.categories);
            context.commit('set_modal_list_fixerman',true);
          }).catch(error => {
            this.loader = false;
          });

    },
    quotations(context,order_id){
        axios.get('/ordenes/cotizaciones/'+order_id).then(response=>{
            context.commit('setQuotations',response.data.quotations);
        });
    },payments(context,order_id){
        axios.get('/pagos?order_id='+order_id).then(response=>{
            context.commit('setPayments',response.data.payments);
        });
    },qualifies(context,order_id){
        axios.get('/ordenes/qualifies/'+order_id).then(response=>{
            console.log(response);
            context.commit('setQualifies',response.data.qualifies);
        });
    }
    },
    watch: {
        dialog(val) {
            if (!val) return
        },
        dialogModal(val) {
            if (!val) return
        },
        modal_list_fixerman(val){
            if (!val) return
        }
    },
});
