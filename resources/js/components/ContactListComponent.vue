<template>
  <b-list-group>
      <a href="#activeConversationComponent">
        <contact-component
  			v-for="conversation in ordered" :key="conversation.id"
  			:conversation="conversation" :selected="isSelected(conversation)"
  			@click.native="selectConversation(conversation)" >

        </contact-component>
      </a>
  </b-list-group>
</template>

<script>
	export default{
		methods:
		{
			selectConversation(conversation){
				this.$store.dispatch('getMessages',conversation);
			},
      isSelected(conversation){
        if (this.selectedConversation)
            return this.selectedConversation.id === conversation.id;

        return false;
      }
		},
    computed:{
      ordered () {
				return _.orderBy(this.conversationsFiltered, 'last_time','DESC')
			},
      selectedConversation(){
        return this.$store.state.selectedConversation;

      },
      conversationsFiltered(){
        return this.$store.getters.conversationsFiltered;
      }
    }
	}
</script>
