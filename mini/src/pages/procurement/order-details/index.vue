<template>
  <div class="pages">
    <i-toast id="toast" />
    订单详情页
  </div>
</template>
<script>
  export default {
    components: {},

    data() {
      return {
        id: ''
      }
    },
    created() {
    },
    onShow() {
    },
    mounted(){
      this.id = this.$root.$mp.query.id
      console.log(this.id)
    },
    methods:{
    }
  }
</script>

<style lang="wxss">
</style>
<style scoped lang="scss">
  .pages{
  }
</style>
