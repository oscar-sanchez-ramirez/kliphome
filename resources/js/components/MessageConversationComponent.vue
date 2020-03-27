<template>
	<b-media :right-align="!!writtenByMe" vertical-align="center" class="mb-2">
		<b-img atl="image" class="m-1" rounded="circle"  height="40px" :src="image" slot="aside"   width="40px"  />
		<b-card><slot></slot></b-card>
	</b-media>
</template>
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
