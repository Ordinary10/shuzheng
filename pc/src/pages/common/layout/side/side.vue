<template>
  <div class="sidebar-wrapper">
    <div class="title">
      <Icon type="ios-car-outline" />
      <span>蜀蒸</span>
    </div>
    <Menu theme="dark">
      <DynamicMenu :menuList="sidebarMenu"></DynamicMenu>
    </Menu>
  </div>
</template>
<script>
import { mapState } from 'vuex'
import DynamicMenu from '@/pages/common/layout/component/dynamic-menu'
export default {
  data () {
    return {}
  },
  computed: {
    ...mapState(['isSidebarNavCollapse', 'loginData', 'crumbList']),
    ...mapState('permission', ['sidebarMenu', 'currentMenu']),
    company_name () {
      if (this.loginData) {
        return this.loginData.userInfo.company_name
      } else {
        return ''
      }
    }
  },
  methods: {
    loginOut () {
      this.$store.commit('LOGIN_OUT')
      this.$router.push('/login')
    },
    refresh () {
      // window.location.reload()
      this.$emit('refresh')
    },
    changeMode () {
      this.$store.commit('changeMode')
    }
  },
  components: {
    DynamicMenu
  },
  mounted () {
  }
}
</script>

<style lang="scss" scoped>
  @mixin active(){
    background :linear-gradient(90deg,rgba(76,129,243,1),rgba(76,195,243,1)) !important;
    color: #fff;
  }
  .sidebar-wrapper{
    width: 200px;
    background-color: #fff;
    height: calc(100% - 15px);
    box-sizing: border-box;
    padding-bottom: 15px;
    border-radius: 0 0 15px 0;
    position: absolute;
    top: 0;
    left: 0;
    overflow: auto;
    &::-webkit-scrollbar{
      display: none;
    }
    .title{
      height: 100px;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 20px;
      color: #4B7BF3;
      letter-spacing: 10px;
      .ivu-icon-ios-car-outline{
        font-size: 30px;
      }
    }
    .ivu-menu{
      background:inherit;
    }
    >.ivu-menu{
      width: 200px!important;
      /*text-align: center;*/
    }
    /deep/ .ivu-menu-dark.ivu-menu-vertical .ivu-menu-item{
      color: #8A98AC;
      font-size: 16px;
      background:inherit;
      padding: 10px 0 10px 27px;
      border-radius: 20px 0 0 20px;
      margin-left: 10%;
      &:hover{
        @include active
      }
      &.ivu-menu-item-selected{
        @include active
      }

    }
    /deep/ .ivu-menu-dark.ivu-menu-vertical .ivu-menu-submenu-title{
      .ivu-icon{
        font-size: 18px;
        transform: translateY(-2px);
      }
      .ivu-icon-ios-arrow-down{
        transform: translateY(-10px);
      }
    }
    /deep/ .ivu-menu-item>i{
      margin: 0;
      font-size: 22px;
      transform: translateY(-2px);
    }
    /deep/ .ivu-menu-dark.ivu-menu-vertical .ivu-menu-submenu{
      background-color: #fff;
      padding: 0 0 0 10px;
      margin-left: 15px;
    }
    /deep/ .ivu-menu-dark.ivu-menu-vertical .ivu-menu-submenu .ivu-menu-submenu-title{
      color: #8A98AC;
      border-radius: 20px 0 0 20px;
      padding-top: 10px;
      padding-bottom: 10px;
      background-color: #fff;
      font-size: 16px;
      &:hover{
        @include active
    }
    }
    /deep/ .ivu-menu-dark.ivu-menu-vertical>.menu-container>.ivu-menu-item{
      margin-left: 24px;
      padding-left: 24px!important;
    }
    /deep/ .ivu-menu-opened .ivu-menu-submenu-title-icon{
      transform: translateY(-10px) rotate(180deg) !important;
    }
  }
</style>
