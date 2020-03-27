<template>
	<b-row style="height:100%;min-height:600px">
        <b-col cols="12">
			<b-card no-body footer-bg-variant="light" footer-border-variant="dark" title="" class="h-85">
				<b-card-body class="card-body-scroll">
					<p>{{ selectedConversation.contact_name["name"]}}</p>
					<message-conversation-component v-for="message in messages" :key="message.id"
					:written-by-me="message.written_by_me"
					:imageFromActive="selectedConversation.contact_name['avatar']">
						<div v-if="message.type == 'text'">
							{{message.content}}
						</div>
						<div v-if="message.type == 'image'">
							<b-img atl="image" class="m-1" :src="message.content" slot="aside"/>
						</div>
					</message-conversation-component>
				</b-card-body>
				<div slot="footer">
					<div v-if="selectedConversation.contact_id == idAdmin || selectedConversation.user_id == idAdmin">
					<b-form class="mb-0" @submit.prevent="postMessage" autocomplete="off">
						<b-input-group id="activeConversationComponent">
							<b-form-input class="text-center" type="text" placeholder="Mensaje" v-model="newMessage"></b-form-input>
							<b-input-group-append>
								<b-button type="submit" variant="primary">Enviar</b-button>
							</b-input-group-append>
						</b-input-group>
					</b-form>
					</div>
				</div>
			</b-card>
        </b-col>
    </b-row>
</template>
<style>
	.card-body-scroll{
		width: 100%;
		overflow-y: scroll;
	}
	main,body,html,#app,.no-gutters{
		height: 100% !important;
	}
	main{
		overflow-y: scroll;
	}
	.card-footer{
		height: 10%;
	}
	@media (max-width:500px){
		.card-footer{
			height: 30%;
		}
	}
	.card-body{
		max-height: 700px;
	}
	.card-body,.h-85{
		height: 100%;
	}
</style>
<script>
	export default{
		data(){
			return{
				newMessage: ''
			};
		},
		mounted(){
		},
		methods:{
			postMessage(){
				this.$store.dispatch('postMessage',this.newMessage).then(()=>{
					this.newMessage = '';
				});

				this.ScrollToBottom();
			},
			ScrollToBottom(){
				const element = document.querySelector('.card-body-scroll');
				element.scrollTop = element.scrollHeight;
			}
		},
		computed:{
			myImage(){
				return this.$store.state.user.avatar;
			},
			idAdmin(){
				return this.$store.state.user.id;
			},
			selectedConversation(){
				return this.$store.state.selectedConversation;
			},
			messages(){
				return this.$store.state.messages;
			}
		},
		updated(){
			this.ScrollToBottom();
		}

	}
</script>
