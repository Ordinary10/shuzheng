<template>
   <div class="top-wrapper">
     <div class="title" v-if="PageMode===1">
       <div class="logo_body"></div>
     </div>
     <div class="sidebar-wrapper" v-if="PageMode===1">
       <Menu mode="horizontal" theme="dark">
         <DynamicMenu :menuList="sidebarMenu"></DynamicMenu>
       </Menu>
     </div>
     <div class="user_info" :class="{'user_info_right':PageMode!==1}">
       <div class="refresh" @click.stop="refresh"><Icon type="md-refresh"/></div>
       <Dropdown placement="bottom">
         <div class="Dropdown">
           <span class="user_img"></span>
           {{username}}
           <Icon type="ios-arrow-down"/>
         </div>
         <DropdownMenu slot="list">
           <DropdownItem  @click.native="settingModal1 = true">
             <Icon type="md-settings"/>系统设置
           </DropdownItem>
           <DropdownItem @click.native="PwdModal1 = true">
             <Icon type="ios-build-outline"/>修改密码
           </DropdownItem>
           <DropdownItem @click.native="loginOut">
             <Icon type="ios-power"/>退出
           </DropdownItem>
         </DropdownMenu>
       </Dropdown>
       <Modal
         v-model="shopModal1"
         title="更换门店"
         :width='400'
         class-name="vertical-center-modal"
         @on-ok="saveShop"
         class="overstepModel"
       >
         <Form :model="shopFormItem" :label-width="50">
           <FormItem label="门店">
             <Select v-model="shopFormItem.select">
               <Option value="beijing">New York</Option>
               <Option value="shanghai">London</Option>
               <Option value="shenzhen">Sydney</Option>
               <Option value="Sydney1">Sydney1</Option>
               <Option value="Sydney2">Sydney2</Option>
               <Option value="Sydney3">Sydney3</Option>
               <Option value="Sydney4">Sydney4</Option>
             </Select>
           </FormItem>
         </Form>
       </Modal>
       <Modal
         v-model="settingModal1"
         title="系统设置"
         :width='400'
         class-name="vertical-center-modal"
         @on-ok="saveInstall"
       >
         <Form :model="formItem" :label-width="100">
           <FormItem label="导航栏位置">
             <RadioGroup v-model="formItem.PageMode">
               <Radio label="1">上</Radio>
               <Radio label="0">左</Radio>
             </RadioGroup>
           </FormItem>
           <FormItem label="多页面模式">
             <i-switch v-model="formItem.TabPage" size="large">
               <span slot="open">是</span>
               <span slot="close">否</span>
             </i-switch>
           </FormItem>
         </Form>
       </Modal>
       <Modal
         v-model="PwdModal1"
         title="修改密码"
         :width='400'
         class-name="vertical-center-modal"
         loading
         @on-ok="savePwd"
       >
         <Form :model="PwdformItem" :label-width="80" ref="Pwdform" :rules="PwdRule">
           <FormItem label="旧密码" prop="pwd">
              <Input v-model="PwdformItem.pwd" type="password" placeholder="请输入旧密码"></Input>
           </FormItem>
           <FormItem label="新密码" prop="newPwd">
             <Input v-model="PwdformItem.newPwd" type="password" placeholder="请输入新密码"></Input>
           </FormItem>
           <FormItem label="新密码" prop="newPwd2">
             <Input v-model="PwdformItem.newPwd2" type="password" placeholder="请再次输入新密码"></Input>
           </FormItem>
         </Form>
         <div slot="footer">
       <Button type="text" size="large" @click="pwdCancel">取消</Button>
       <Button type="primary" size="large" @click="savePwd" @keyup.enter.native="savePwd">确定</Button>
      </div>
       </Modal>
     </div>
   </div>
</template>

<script>
import { mapState } from 'vuex'
import DynamicMenu from './dynamic-menu'
export default {
  data () {
    return {
      settingModal1: false,
      PwdModal1: false,
      shopModal1: false,
      formItem: {
        TabPage: true,
        PageMode: '1'
      },
      shopFormItem: {
        select: 'shanghai'
      },
      PwdformItem: {
        pwd: '',
        newPwd: '',
        newPwd2: ''
      },
      PwdRule: {
        pwd: [
          {required: true, message: '必输项不能为空', trigger: 'blur'},
          {type: 'string', min: 6, message: '不能少于6个字符', trigger: 'blur'}
        ],
        newPwd: [
          {required: true, message: '必输项不能为空', trigger: 'blur'},
          {type: 'string', min: 6, message: '不能少于6个字符', trigger: 'blur'}
        ],
        newPwd2: [
          {required: true, message: '必输项不能为空', trigger: 'blur'},
          {type: 'string', min: 6, message: '不能少于6个字符', trigger: 'blur'},
          {validator: (rule, value, callback) => {
            if (this.PwdformItem.newPwd !== value) {
              callback(new Error('两次输入的密码不一致'))
            } else {
              callback()
            }
          },
          trigger: 'blur'}
        ]
      }
    }
  },
  created () {
    this.formItem.TabPage = this.TabPage === '1'
    this.formItem.PageMode = String(this.PageMode)
  },
  computed: {
    ...mapState(['isSidebarNavCollapse', 'loginData', 'crumbList', 'PageMode', 'TabPage']),
    ...mapState('permission', ['sidebarMenu', 'currentMenu']),
    username () {
      if (this.loginData) {
        return this.loginData.userInfo.username
      } else {
        return ''
      }
    }
  },
  methods: {
    loginOut () {
      this.$store.commit('LOGIN_OUT')
      // this.$router.push('/login')
      // 重置vuex 防止数据紊乱
      window.location.reload()
    },
    refresh () {
      // window.location.reload()
      this.$emit('refresh')
    },
    // 模式 tab设置
    saveInstall () {
      this.$store.commit('changeMode', this.formItem.PageMode)
      this.$store.commit('changeTab', this.formItem.TabPage ? 1 : 0)
    },
    saveShop () {
      alert('门店')
    },
    pwdCancel () {
      this.PwdModal1 = false
    },
    savePwd () {
      this.$refs.Pwdform.validate(async valid => {
        if (valid) {
          let obj = {
            old_pwd: this.PwdformItem.pwd,
            new_pwd1: this.PwdformItem.newPwd,
            new_pwd2: this.PwdformItem.newPwd2
          }
          let res = await this.$axios('user/changePwd', obj)
          if (res.code === 1) {
            let _this = this
            this.$Message.success({
              content: res.msg,
              duration: 1,
              onClose: () => {
                _this.PwdModal1 = false
                _this.$store.commit('LOGIN_OUT')
                _this.$router.push({path: '/login'})
              }
            })
          }
        } else {
          return false
        }
      })
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
  background:#1572e8;
  display: flex;
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
      background-color: #1572e8;
      min-width: 80% !important;
      text-align: center;
      .ivu-menu-item:hover{
        background-color:#115bba;
      }
      .ivu-menu-item.ivu-menu-item-active{
        background-color: #1572e8;
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
    letter-spacing: 10px;
    .logo_body{
      margin-top: 8px;
      height: 67px;
      width: 67px;
      border-radius:50%;
      background-repeat: no-repeat;
      background-position: center;
      background-color: #fff;
      box-shadow:0px 0px 6px 1px rgba(9,60,124,0.21);
      background-image: url("http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15682765267335.png");
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
      background-color:#115bba;
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
        background: url("../../../../../static/images/logo.png") no-repeat center center;
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
