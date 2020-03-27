<template>
	<b-media :right-align="!writtenByMe" vertical-align="center" class="mb-2">
		<b-img atl="image" class="m-1" rounded="circle" id="imgUser" :src="image" slot="aside"  />
		<b-card><slot></slot></b-card>
	</b-media>
</template>
<style>
	#imgUser{
		height: 40px;
		width: 40px;
		padding-bottom: 5%;
	}
</style>
<script>
	export default{
    props:{
      	writtenByMe: Number,
		imageFromActive:String
	},
	mounted(){
		if(this.writtenByMe == 1){
			this.image = this.imageFromActive;
		}else{
			this.image = this.myImage;
		}
	},
	data(){

		return{
			content : 'abc',image:''
		};
	},
	computed:{
		myImage(){
			return this.$store.state.user.avatar;
		},
	}
	}
</script>
