import Vue from 'vue'
import Vuex from 'vuex'
import moment from 'moment'

Vue.use(Vuex);
export default new Vuex.Store({
    state: {
        // chat
        dialog: false,
        dialogModal: false,
        messages: [],
        selectedConversation: null,
        conversations: [],
        querySearch: '',
        user: null,
        credentials:[],
        conversationFromNotification:null,
        // orders
        orders:[]
    },
    mutations: {
        // Chat
        setDialog(state, value) {
            state.dialog = value;
        },
        setDialogModal(state, value) {
            state.dialogModal = value;
        },
        setUser(state,user){
          state.user = user;
        },
        setConversation(state,conversationFromNotification){
          state.conversationFromNotification = conversationFromNotification
        },
        newMessagesList(state, messages){
          state.messages = messages;
        },
        addMessage(state,message){
          message.type = 'text';
          const conversation = state.conversations.find((conversation)=>{
            return conversation.id == message.conversation_id;
          });
          const author = state.user.id === message.from_id ? 'Tú' : conversation.contact_name["name"];

          conversation.last_message = author+': '+message.content;
          conversation.last_time = message.created_at;
          if (state.selectedConversation.id == message.conversation_id) {
              state.messages.push(message);
          }
        },
        selectConversation(state, conversation){
            state.selectedConversation = conversation;
        },
        newQuerySearch(state,newValue){
          state.querySearch = newValue;
        },
        newConversationsList(state, conversations){
          state.conversations = conversations;
        },
        // order
        orderList(state,orders){
            state.orders = orders;
        }

    },
    actions: {
      getMessages(context,conversation){
        axios.get('/api/messages?contact_id='+conversation.contact_id+'&user_id='+conversation.user_id+'&conversation_id='+conversation.id).then(
          response=>{
            context.commit('selectConversation',conversation);
            context.commit('newMessagesList',response.data);
          }
        );
      },
      getConversations(context,type){
        axios.get('/api/conversations/'+type).then((response) => {
          let array = response.data.sort( ( a, b) => {
              return new Date(a.last_time) - new Date(b.last_time);
          });
          array.reverse();

          if(this.state.conversationFromNotification != 0){
            for (let index = 0; index < response.data.length; index++) {
              if(this.state.conversationFromNotification == response.data[index]["order_id"]){
                this.state.selectedConversation = response.data[index];
                this.dispatch('getMessages',response.data[index]);
              }
             }
          }else{
            this.state.selectedConversation = array[0];
            this.dispatch('getMessages',array[0]);
          }

          context.commit('newConversationsList',array);
        });
      },
      postMessage(context,newMessage){
        const params = {
          to_id: context.state.selectedConversation.user_id,
          conversation_id:context.state.selectedConversation.id,
          content: newMessage
        };
        return axios.post('/api/messages',params).then((response) => {
          if(response.data.success)
          {
            newMessage = '';
            const message = response.data.message;
            message.written_by_me = 0;
            context.commit('addMessage',message);
          }
        });
      },
      // Orders
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
      }
    },
    getters:{
      conversationsFiltered(state){
        return state.conversations.filter(
          conversation =>
            conversation.contact_name["name"]
            .toLowerCase()
            .includes(state.querySearch.toLowerCase())
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
