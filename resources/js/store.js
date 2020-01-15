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
          const conversation = state.conversations.find((conversation)=>{
            return conversation.contact_id == message.from_id || conversation.contact_id == message.to_id;
          });
          const author = state.user.id === message.from_id ? 'Tú' : conversation.contact_name["nombres"];

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
        axios.get('/api/messages?contact_id='+conversation.contact_id).then(
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
          context.commit('newConversationsList',response.data);
        });
      },
      postMessage(context,newMessage){
        const params = {
          to_id: context.state.selectedConversation.contact_id,
          content: newMessage
        };
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
      getAccess(context){
        const params = {
          grant_type: "password",
          client_id : 7,
          client_secret : "iv7El0IegHgStzhQJy8QWWQovRJIGkR3zTLI8f79",
          username: "admin@kliphome.com",
          password: "kliphome2019"
        };
        return axios.post('/oauth/token',params).then((response) => {
          this.state.credentials = response.data;
          console.log(this.state.credentials.access_token);
        });

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