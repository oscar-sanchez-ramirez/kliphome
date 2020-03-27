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
        credentials:[]
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
            context.commit('selectConversation',conversation);
            console.log(response.data);
            context.commit('newMessagesList',response.data);
          }
        );
      },
      getConversations(context,type){
        axios.get('/api/conversations/'+type).then((response) => {
          if(response.data != ""){
            this.state.selectedConversation = response.data[0];
          }
          // for (let index = 0; index < response.data.length; index++) {
          //   if(index > 0){
          //     if(response.data[index].contact_id == response.data[index-1].user_id){
          //       response.data[index-1].group = response.data[index-1].contact_name.name+' '+response.data[index-1].contact_name.lastName+' con '+response.data[index].contact_name.name+' '+response.data[index].contact_name.lastName;
          //       response.data.splice(response.data.indexOf(response.data[index]), 1);
          //     }
          //   }
          // }
          let array = response.data.sort( ( a, b) => {
              return new Date(a.last_time) - new Date(b.last_time);
          });
          // array.reverse();
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