<template>
  <b-container fluid style="height: 100%;margin-top:20px;">
    <b-row no-gutters v-if="checkConversation != '' ">
        <div class="col-md-4 listado-contactos">
          <b-tabs card>
            <b-tab title="Mis Mensajes" active>
              <contact-form-component />
              <contact-list-component />
            </b-tab>
            <b-tab title="Otros">
              <b-card-text>Tab contents 2</b-card-text>
            </b-tab>
          </b-tabs>

        </div>
        <div class="col-md-8">
            <active-conversation-component v-if="selectedConversation" />
        </div>
    </b-row>
    <b-row v-else-if="checkConversation == '' ">
       <div class="mx-auto" >
          <b>Aún no tienes conversaciones</b>
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
      message.written_by_me = 0;
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
