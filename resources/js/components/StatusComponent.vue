<template>
  <b-img rounded="circle" blank width="10" height="10"
  :title="online ? 'Conectado':'Desconectado'"
  :blank-color="online ? 'green':'gray'" src=""  alt="img" class="m-1" />
</template>

<script>
export default {
  props:{
    online:Boolean
  }
}
</script>
