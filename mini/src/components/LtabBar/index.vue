<template>
  <cover-view  class="content" v-if="tabs.length>1">
    <cover-view class="ul">
      <cover-view  v-for="item of get_role" :key="item.id" @click="selectNavItem(item.path)" :class="[{'active':item.path === nowPath},'.li']">
        <cover-image v-show="item.path !== nowPath" class="img" :src="item.img"></cover-image>
        <cover-image v-show="item.path === nowPath" class="img" :src="item.active_img"></cover-image>
        <cover-view class="name">{{item.name}}</cover-view >
      </cover-view >
    </cover-view >
  </cover-view >
</template>

<script>
  export default {
    name:'l-tab-bar',
    props:{
      nowPath:String
    },
    data() {
      return {
        tabs:[]
      }
    },
    created() {
    },
    mounted(){
      this.tabs = this.get_role
    },
    onShow(){
      this.tabs = this.get_role
    },
    methods: {
      /**
       * 点击导航栏
       * @param pagePath 跳转的页面路径
       */
      selectNavItem (pagePath) {
        if (pagePath === this.nowPath) {
          return false;
        }
        wx.switchTab({ url: pagePath })
      }
    },
    computed: {
      //role不能写到mounted里面，否则切换账号时该组件不会更新
      get_role () {
        let tabs = this.$common.getRole()
        return tabs
      }
    }
  }
</script>

<style scoped lang="scss">
  .content{
    position: fixed;
    bottom: 0;
    width: 100%;
    left: 0;
    right: 0;
    height: 50px;
    z-index: 999;
    background-color: #fff;
  }
  .ul{
    display: flex;
    height: 100%;
    border-top: 1px solid #ccc;
    .li{
      flex: 1;
      margin: 8px 20% 0;
      height: 100%;
      text-align: center;
      color:#999999;
      font-size: 10px;
      .img{
        width: 20px;
        height: 20px;
        margin: auto;
      }
      .name{
        margin: 5px 0 0;
      }
      &.active{
        color: #04A9F5;
        border-bottom: 10px solid #04A9F5;
        box-sizing: border-box;
      }
    }
  }
</style>
