<template>
  <b-container fluid style="height: 100%;margin-top:20px;">
    <b-row no-gutters>
        <div class="col-md-4 listado-contactos">
          <b-tabs card>
            <b-tab title="Mis Mensajes" active @click="getOtherConversations('admin')">
              <contact-form-component />
              <contact-list-component />
            </b-tab>
            <b-tab title="Otros" @click="getOtherConversations('user')">
              <contact-form-component />
              <contact-list-component />
            </b-tab>
          </b-tabs>

        </div>
        <div class="col-md-8">
            <active-conversation-component v-if="selectedConversation" />
        </div>
    </b-row>
  </b-container>
</template>
<style>
		.listado-contactos{
		  max-height:700px;
			width: 100%;
		  overflow-y: hidden;
    }
</style>
<script>
export default{
  props:{
    user: Object
  },
  mounted(){
    this.$store.commit('setUser',this.user);
    // this.$store.dispatch('getAccess');
    this.$store.dispatch('getConversations','admin');
    Echo.private('users.'+this.user.id).listen('MessageSent',(data)=>{
      const message = data.message;
      message.written_by_me = 1;
      this.addMessage1(message);
    });
    Echo.join('messenger')
    .here((users)=>{
      users.forEach(user => this.changeStatus(user,true));
    }).joining(
      user => this.changeStatus(user,true)
    ).leaving(
      user => this.changeStatus(user,false)
    );

  },
  methods:
  {
    getOtherConversations(type){
      this.$store.dispatch('getConversations',type);
      this.$store.state.selectedConversation = null;
      this.$store.state.messages = [];
    },
    changeStatus(user,status){
      const index = this.$store.state.conversations.findIndex((conversation)=>{
          return conversation.contact_id == user.id;
      });
      if (index >= 0) {
          this.$set(this.$store.state.conversations[index],'online',status);
      }
    },
    addMessage1(message){
      return this.$store.commit('addMessage',message);
    }
  },
  computed:{
    selectedConversation(){
      return this.$store.state.selectedConversation;
    },
    checkConversation(){
      return this.$store.state.conversations;
    },
    addMessage(message){
      return this.$store.commit('addMessage',message);
    }
  }
}
</script>
