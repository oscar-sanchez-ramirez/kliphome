import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex);
export default new Vuex.Store({
    state: {
        dialog: false,
        dialogModal: false,
        messages: [],
        selectedConversation: null,
        conversations: [],
        querySearch: '',
        user: null,
        credentials:[],
        conversationFromNotification:null
    },
    mutations: {
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
            return conversation.contact_id == message.from_id || conversation.contact_id == message.to_id;
          });
          const author = state.user.id === message.from_id ? 'Tú' : conversation.contact_name["name"];

          conversation.last_message = author+': '+message.content;
          conversation.last_time = message.created_at;
          if (state.selectedConversation.contact_id == message.from_id || state.selectedConversation.contact_id == message.to_id) {
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
    },
    actions: {
      getMessages(context,conversation){
        axios.get('/api/messages?contact_id='+conversation.contact_id+'&user_id='+conversation.user_id+'&conversation_id='+conversation.id).then(
          response=>{
            console.log("old: "+this.state.selectedConversation.id);
            console.log('new: '+conversation.id);
            window.Echo.connector.socket.removeListener('users'+this.state.selectedConversation.id);
            context.commit('selectConversation',conversation);
            context.commit('newMessagesList',response.data);
            this.dispatch('openChannel',conversation.id);
          }
        );
      },
      getConversations(context,type){
        axios.get('/api/conversations/'+type).then((response) => {
          // if(response.data != ""){
          // }
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
            this.dispatch('openChannel',array[0].id);

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
      openChannel(context,id){
        console.log(id)
        window.Echo.private('users.'+id).listen('MessageSent',(data)=>{
          const message = data.message;
          console.log(message);
          message.written_by_me = 1;
          context.commit('addMessage',message);
        });
      },
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