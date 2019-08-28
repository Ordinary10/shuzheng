<template>
  <transition name="fade">
      <router-view class="content-wrapper" :style="{'padding':PageMode===1?'75px 15px 15px':'75px 15px 15px 255px'}" v-if="refresh"></router-view>
  </transition>
</template>

<script>
export default {
  props: {
    // 刷新组件
    active: {
    },
    // 页面模式
    PageMode: {
    }
  },
  data () {
    return {
      refresh: true
    }
  },
  mounted () {
  },
  watch: {
    // 刷新组件
    active () {
      this.refresh = false
      this.$nextTick(() => (this.refresh = true))
    }
  }
}
</script>

<style lang="scss" scoped>
  .fade-enter-active{
    animation:bounce-in 0.3s;
  }
  .fade-leave-active{
    animation:bounce-in 0.3s reverse;
  }
  @keyframes bounce-in{
  }

  .content-wrapper{
    padding: 75px 15px 15px;
    height:100%;
    border-radius:15px;
    /deep/ >div{
      height:100%;
      display: flex;
      flex-direction: column;
    }

  }
</style>
