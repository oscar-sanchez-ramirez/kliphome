import Vue from 'vue'
import Vuex from 'vuex'
import moment from 'moment'

Vue.use(Vuex);
export default new Vuex.Store({
    state: {
        // chat
        dialog: false,
        // orders
        orders:[]
    },
    mutations: {
        // Chat
        setDialog(state, value) {
            state.dialog = value;
        },
        // order
        orderList(state,orders){
            state.orders = orders;
        }

    },
    actions: {
      // Orders
      orders(context,tecnico){
        axios.get('/tecnicos/ordenes_tecnico/'+tecnico.id).then(
            response=>{
              let orders = response.data;
              console.log(orders);
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
                console.log(events);
              context.commit('orderList',events);
            }
          );
      }
    },
    watch: {
        dialog(val) {
            if (!val) return
        },
        dialogModal(val) {
            if (!val) return
        }
    }
});
