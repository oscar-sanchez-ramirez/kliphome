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
	}
</style>
<script>
	export default{
    props:{
      	writtenByMe: Number,
		image:String
    },
	data(){
		return{
			content : 'abc'
		};
	}
	}
</script>
