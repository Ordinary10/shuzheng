<template>
  <div class="page-login">
    <i-toast id="toast" />
    <div class="login-bgc-img">
      <img class="image" src="http://zucheguanjia.oss-cn-qingdao.aliyuncs.com/car/15674071146089.png" alt="">
    </div>
    <div class="login-form">
      <div class="input-box">
        <icon class="iconfont iconyonghuming"></icon>
        <input type="text" @confirm="next_input" confirm-type="next" v-model="form.account" placeholder="用户名/手机号">
      </div>
      <div class="input-box">
        <icon class="iconfont iconmima"></icon>
        <input type="text" :focus="isFocus" @blur="isFocus=false" :password="true" @confirm="login" confirm-type="done" v-model="form.pwd" placeholder="密码">
      </div>
      <button type="button" @click="login" class="login_btn">登录</button>
    </div>
  </div>
</template>

<script>
  export default {
    components: {},
    data() {
      return {
        form:{
          account: '',
          pwd: '',
          code: ''
        },
        isFocus:false
      }
    },

    created() {
    },
    onShow() {
      /*自动登录功能*/
      // this.autoLogin()
    },
    mounted(){
    },
    methods:{
      /*自动登录*/
      autoLogin(){
        const _this = this
        wx.login({
          success:function (res) {
            _this.$ajax('login/mimiProgramLogin',{code:res.code},function (res){
              _this.loginSuccess(res)
            },function (err) {
              _this.$common.normal_tip('登录失败，请使用账号密码登录','',1000)
            },'自动登陆中')
          },
          fail:function (err) {
            // _this.$common.normal_tip('自动登录失败，请使用账号密码登录')
          }
        })
      },
      /*账号密码登录*/
      login(){
        const _this = this
        if (_this.form.account === "") {
          return _this.$common.normal_tip("请输入登录账号");
        }
        if (_this.form.pwd === "") {
          return _this.$common.normal_tip("请输入登录密码");
        }
        wx.login({
          success: (res) => {
            let code = res.code
            if(!code){
              return _this.$common.normal_tip('小程序登录失败，请重试')
            }
            _this.form.code = code
            _this.$ajax('login/mimiProgramLogin',_this.form,function (res){
              _this.loginSuccess(res)
            },function (res) {
              _this.$common.normal_tip(res.msg)
            },'登陆中。。。')
          },
          fail: (res)=>{
            _this.$common.normal_tip('小程序登录失败，请重试')
          }
        })
      },
      /*登陆成功后的信息处理*/
      loginSuccess(res){
        const _this = this
        if(res.code === 1){
          /*存储token和role*/
          _this.$store.commit('setToken', res.data.token)
          _this.$store.commit('setRole', res.data.show_page.role)
          /*存储基础数据*/
          _this.$common.getPageInfo().then(response => {
            _this.$store.commit('setInitData', response.data)
            /*根据role进入对应角色的首页*/
            let tabList = _this.$common.getRole()
           if(tabList && tabList.length>0){
             _this.$common.success_tip('登录成功',function () {
               wx.switchTab({
                 url: tabList[0].path
               })
             })
           } else {
             _this.$common.error_tip('该角色权限不足')
           }
          }).catch(err => {
            console.log(err)
          })
        } else {
          common.error_tip(res.msg)
        }
      },
      /*密码框聚焦*/
      next_input(){
        this.isFocus=true
      }
    }
  }
</script>

<style scoped lang="scss">
  @import "../../assets/variables";
.page-login{
  button{
    border: none;
  }
  .image{
    width: 100%;
    height: 352px;
  }
  .login-form{
    padding: 30px 47px 0;
    .login_btn{
      width:278px;
      font-size: 18px;
      color: white;
      line-height: 40px;
      background:$mainColor;
      box-shadow:0px 7px 15px 3px rgba(236, 31, 24, 0.35);
      border-radius:20px;
      margin-top: 50px;
      &:hover{
        background: #EC5B5A;
      }
    }
    .input-box{
      position: relative;
      height: 50px;
      width: 100%;
      display: flex;
      justify-content: flex-start;
      align-items: center;
      border-bottom: 1px solid #DCDEE2;
      box-sizing: border-box;
      padding: 0 11px;
      margin: 15px 0;
      icon{
        font-size: 13px;
        color: $mainColor;
        line-height: 50px;
      }
      input{
        color: #595959;
        font-size: 12px;
        margin-left: 10px;
        line-height: 50px;
        height: 50px;
        flex: 1;
      }
    }
  }
}
</style>
