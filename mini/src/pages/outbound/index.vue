<template>
  <div class="page-login">
    <i-toast id="toast" />
    出库单
    <LtabBar nowPath="/pages/outbound/main" key="outbound"></LtabBar>
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
