<template>
  <b-container fluid style="height: 100%;margin-top:20px;">
    <b-row no-gutters v-if="checkConversation != '' ">
        <div class="col-md-4 listado-contactos">
            <contact-form-component />
            <contact-list-component />
        </div>
        <div class="col-md-8">
            <active-conversation-component v-if="selectedConversation" />
        </div>
    </b-row>
    <b-row v-else-if="checkConversation == '' ">
       <div class="mx-auto" >
          <b>Aún no tienes conversaciones, para hacerlo deberas iniciarlas desde el administrador de tus anuncios</b>
       </div>

    </b-row>
    <b-row v-if="checkConversation == '' ">
      <br>
      <div class="mx-auto" style="padding-top:4%">
         <form action="/perfil">
           <b-button type="submit" style="background-color:#ca90f4;color:white">Ir a mis anuncios</b-button>
         </form>
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
    console.log(this.user);
    this.$store.commit('setUser',this.user);
    this.$store.dispatch('getConversations');
    this.$store.dispatch('getAccess');
    window.Echo.private('users.'+this.user.id)
        .listen('MessageSent', function (data) {
            console.log(data);
            const message = data.message;
            message.written_by_me = 0;
            this.addMessage1(message);
        });


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
