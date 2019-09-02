<template>
  <cover-view  class="content">
    <cover-view class="ul" v-if="tabs.length>1">
      <cover-view  v-for="item of navData[get_role]" :key="item.id" @click="selectNavItem(item.path)" :class="[{'active':item.path === nowPath},'.li']">
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
        navData:{
          admin:[
            {
              name: '采购',
              path: '/pages/procurement/main',
              img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875747481.png',
              active_img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875775113.png'
            },
            {
              name: '出库',
              path: '/pages/outbound/main',
              img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875808670.png',
              active_img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875855758.png'
            }
          ],
          procurement:[
            {
              name: '采购',
              path: '/pages/procurement/main',
              img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875747481.png',
              active_img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875775113.png'
            }
          ],
          outbound: [
            {
              name: '出库',
              path: '/pages/outbound/main',
              img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875808670.png',
              active_img: 'http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15673875855758.png'
            }
          ]
        },
        tabs:[]
      }
    },
    created() {
      this.tabs = this.navData[this.get_role]||['admin']
    },
    mounted(){
    },
    onShow(){
      this.tabs = this.navData[this.get_role]||['admin']
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
        return this.$common.getRole()
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
    height: 100rpx;
    z-index: 999;
    background-color: #fff;
  }
  .ul{
    display: flex;
    height: 100%;
    border-top: 1px solid #ccc;
    .li{
      flex: 1;
      margin: 15rpx 20% 0;
      height: 100%;
      text-align: center;
      color:#999999;
      font-size: 20rpx;
      .img{
        width: 40rpx;
        height: 40rpx;
        margin: auto;
      }
      .name{
        margin: 10rpx 0 0;
      }
      &.active{
        color: #04A9F5;
        border-bottom: 20rpx solid #04A9F5;
        box-sizing: border-box;
      }
    }
  }
</style>
