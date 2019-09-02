<template>
  <div class="page-login">
    <i-toast id="toast" />
    采购单
    <LtabBar nowPath="/pages/procurement/main" key="procurement"></LtabBar>
  </div>
</template>

<script>
  import LtabBar from '@/components/LtabBar/index'
  export default {
    components: {LtabBar},

    data() {
    },

    created() {
    },
    mounted(){
      wx.hideTabBar()
    },
    methods:{
    }
  }
</script>

<style scoped lang="scss">
</style>
