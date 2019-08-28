<template>
   <div class="top-wrapper">
     <div class="title" v-if="PageMode===1">
       <Icon type="ios-car-outline" />
       <span>租车管家</span>
     </div>
     <div class="sidebar-wrapper" v-if="PageMode===1">
       <Menu mode="horizontal" theme="dark">
         <DynamicMenu :menuList="sidebarMenu"></DynamicMenu>
       </Menu>
     </div>
     <div class="user_info" :class="{'user_info_right':PageMode!==1}">
       <div class="refresh" @click.stop="refresh"><Icon type="md-refresh" /></div>
       <Dropdown placement="bottom">
         <div class="Dropdown">
           <span class="user_img"></span>
           {{company_name}}
           <Icon type="ios-arrow-down"></Icon>
         </div>
         <DropdownMenu slot="list">
           <DropdownItem>
             <Icon type="md-color-filter" />更换公司
           </DropdownItem>
           <DropdownItem  @click.native="changeMode">
             <Icon type="md-color-filter"/>更换模式
           </DropdownItem>
           <DropdownItem  @click.native="changeTab">
             <Icon type="md-color-filter"/>切换tab
           </DropdownItem>
           <DropdownItem>
             <Icon type="ios-build-outline" />修改密码
           </DropdownItem>
           <DropdownItem @click.native="loginOut">
             <Icon type="ios-power" />退出
           </DropdownItem>
         </DropdownMenu>
       </Dropdown>
     </div>
   </div>
</template>

<script>
import { mapState } from 'vuex'
import DynamicMenu from './dynamic-menu'
export default {
  data () {
    return {}
  },
  computed: {
    ...mapState(['isSidebarNavCollapse', 'loginData', 'crumbList', 'PageMode']),
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
    },
    changeTab () {
      this.$store.commit('changeTab')
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
.top-wrapper{
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 60px;
  background:rgba(76,124,243,1);
  display: flex;
  z-index: 1000;
  .sidebar-wrapper{
    flex: 1;
    background:inherit;
    .ivu-menu{
      background:inherit;
    }
    /deep/ .ivu-menu-dark.ivu-menu-horizontal .ivu-menu-submenu,/deep/ .ivu-menu-dark.ivu-menu-horizontal .ivu-menu-item{
      color: #fff;
      font-size: 16px;
      .ivu-icon{
        font-size: 18px;
        transform: translateY(-2px);
      }
      .ivu-icon-ios-arrow-down{
        font-size: 18px;
        transform: translateY(0);
      }
    }
    /deep/ .ivu-menu-dark.ivu-menu-horizontal>.menu-container>.ivu-menu-submenu,.ivu-menu-dark.ivu-menu-horizontal>.menu-container>.ivu-menu-item{
      padding: 0 10px;
    }
    /deep/ .ivu-menu-opened .ivu-menu-submenu-title-icon{
      transform: translateY(0) rotate(180deg) !important;
    }
    /deep/ .ivu-select-dropdown{
      background-color: rgba(76,124,243,1);
      min-width: 80% !important;
      text-align: center;
      .ivu-menu-item:hover{
        background-color:#054ff5;
      }
      .ivu-menu-item.ivu-menu-item-active{
        background-color: #054ff5;
      }
    }
  }
  .title{
    width: 12%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 20px;
    color: #fff;
    .ivu-icon-ios-car-outline{
      margin: 0 10px;
      font-size: 30px;
    }
  }
  .user_info{
    display: flex;
    margin-right: 10px;
    &.user_info_right{
      position: absolute;
      right: 0;
    }
    .refresh{
      height: 100%;
      padding: 13px;
      text-align: center;
      .ivu-icon-md-refresh{
        color: #fff;
        font-size: 34px;
        cursor: pointer;
      }
    }
    .refresh:hover{
      background-color:#054ff5;
    }
    /*下拉框*/
    .ivu-dropdown{
      flex: 1;
      height: 100%;
    }
    .Dropdown{
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 60px;
      text-align: center;
      font-size: 17px;
      .ivu-icon-ios-arrow-down{
        margin-left: 5px;
        transition: transform .2s ease-in-out;
      }
      &:hover{
        .ivu-icon-ios-arrow-down{
          transform: rotate(180deg);
        }
      }
      .user_img{
        display: inline-block;
        width: 30px;
        height: 30px;
        background: url("../../../../../static/images/face.jpg") no-repeat center center;
        border-radius: 50%;
        background-size: 30px 30px;
        margin: 5px 10px;
      }
    }
    .ivu-dropdown-menu{
      .ivu-icon{
        font-size: 16px !important;
        color: #515a6e;
        margin: 0 5px;
      }
    }
  }
}
</style>
