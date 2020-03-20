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
          const author = state.user.id === message.from_id ? 'Tú' : conversation.contact_name["nombres"];

          conversation.last_message = author+': '+message.content;
          conversation.last_time = message.created_at;
          if (state.selectedConversation.contact_id == message.from_id || state.selectedConversation.contact_id == message.to_id) {
              state.messages.push(message);
          }
          console.log(state.conversations);
          console.log(state.selectedConversation);
          console.log(state.messages);
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
      // getAccess(context){
      //   const params = {
      //     grant_type: "password",
      //     client_id : 2,
      //     client_secret : "knnI4v0lLc34VyKg6vR25pWMWTstx2dB4I69bqfq",
      //     username: "admin@kliphome.com",
      //     password: "kliphome2019"
      //   };
      //   return axios.post('/oauth/token',params).then((response) => {
      //     this.state.credentials = response.data;
      //     console.log(this.state.credentials);
      //     console.log(this.state.credentials.access_token);
      //   });

      // },
      getMessages(context,conversation){
        axios.get('/api/messages?contact_id='+conversation.contact_id+'&user_id='+conversation.user_id).then(
          response=>{
            context.commit('selectConversation',conversation);
            context.commit('newMessagesList',response.data);
          }
        );
      },
      getConversations(context){
        axios.get('/api/conversations').then((response) => {
          if(response.data != ""){
            this.state.selectedConversation = response.data[0];
          }
          for (let index = 0; index < response.data.length; index++) {
            if(index > 0){
              if(response.data[index].contact_id == response.data[index-1].user_id){
                response.data[index-1].group = response.data[index-1].contact_name.name+' '+response.data[index-1].contact_name.lastName+' con '+response.data[index].contact_name.name+' '+response.data[index].contact_name.lastName;
                response.data.splice(response.data.indexOf(response.data[index]), 1);
              }
            }
          }
          let array = response.data.sort( ( a, b) => {
              return new Date(a.last_time) - new Date(b.last_time);
          });
          array.reverse();
          context.commit('newConversationsList',array);
        });
      },
      postMessage(context,newMessage){
        const params = {
          to_id: context.state.selectedConversation.contact_id,
          content: newMessage
        };
        console.log(params);
        return axios.post('/api/messages',params).then((response) => {
          if(response.data.success)
          {
            newMessage = '';
            const message = response.data.message;
            message.written_by_me = 1;
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