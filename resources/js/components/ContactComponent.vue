<template>
    <b-list-group-item :variant="variant">
        <b-row class="p-2" align-h="center">
            <b-col cols="3" md="3" class="text-center">
                <b-img rounded="circle" width="60" style="border-radius:100%;border: 2px solid #ca90f4; padding:2px" height="60" :src="conversation.contact_name['avatar']"  alt="img" class="m-1"></b-img>
            </b-col>
            <b-col cols="9">

                <p class="mb-0">
                  <status-component :online="conversation.online"></status-component>

                  {{ conversation.contact_name["name"] }}
                </p>
                <p class="text-muted small mb-1">{{ conversation.last_message }}</p>

            </b-col>
            <b-col cols="6" class="d-none d-md-block">
                <p class="text-muted small">{{ lastTime }}</p>
            </b-col>
        </b-row>
    </b-list-group-item>
</template>

<script>
    export default {
        props: {
            conversation:Object,
            selected: Boolean
        },
        mounted(){
            console.log(this.conversation);
        },
        computed:{
          lastTime(){
            return moment(this.conversation.last_time,'YYYY-MM-DD hh:mm:ss').locale('es').fromNow();
          },
          variant(){
            return this.selected ? 'info' : '';
          }
        }
    }
</script>
