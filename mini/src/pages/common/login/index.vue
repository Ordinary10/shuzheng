<template>
  <div class="page-login">
    <i-toast id="toast" />
    <div class="login-bgc-img">
      <img class="image" src="http://test.c.zdxrchina.com/images/wechat/login_bgc2.jpg" alt="">
    </div>
    <div class="login-form">
      <div class="input-box">
        <icon class="iconfont icon-yonghuming"></icon>
        <input type="text" @confirm="next_input" confirm-type="next" v-model="form.account" placeholder="用户名/手机号">
      </div>
      <div class="input-box">
        <icon class="iconfont icon-mima"></icon>
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
    mounted(){
      // this.autoLogin()
    },
    methods:{
      /*自动登录*/
      autoLogin(){
        const _this = this
        // wx.showLoading({ title: "自动登陆中..." })
        wx.login({
          success:function (res) {
            _this.$ajax('login/mimiProgramLogin',{code:res.code},function (res) {
              _this.$store.commit('setToken', res.data.token)
              // _this.$store.commit('setRole', res.data.show_page.role)
              _this.$common.normal_tip('自动登录成功')
              wx.switchTab({
                url:'/pages/admin/search/main'
              })
            },function (err) {
              _this.$common.normal_tip('自动登录失败，请使用账号密码登录','',1000)
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
            _this.$ajax('login/mimiProgramLogin',_this.form,function (res) {
              if(res.status === 1){
                _this.$store.commit('setToken', res.data.token)
                // _this.$store.commit('setRole', res.data.show_page.role)
                wx.switchTab({
                  url:'/pages/admin/search/main'
                })
              }
            },function (res) {
              _this.$common.normal_tip(res.msg)
            })
          },
          fail: (res)=>{
            _this.$common.normal_tip('小程序登录失败，请重试')
          }
        })
      },
      /*密码框聚焦*/
      next_input(){
        this.isFocus=true
      }
    }
  }
</script>

<style scoped lang="scss">
.page-login{
  button{
    border: none;
  }
  .image{
    width: 100%;
    height: 440rpx;
  }
  .login-form{
    padding: 100rpx 94rpx 0;
    .get_code{
      width:126rpx;
      line-height: 55rpx;
      background:rgba(24,125,255,0.2);
      border-radius:20rpx;
      position: absolute;
      top: 8rpx;
      right: 0;
      padding: 0;
      font-size: 20rpx;
      z-index: 1000;
    }
    .login_btn{
      width:556rpx;
      font-size: 36rpx;
      color: white;
      line-height: 80rpx;
      background:rgba(4,169,245,1);
      box-shadow:0px 7rpx 15rpx 3rpx rgba(4,169,245,0.35);
      border-radius:40rpx;
      margin-top: 150rpx;
      &:hover{
        background: rgb(38, 113, 245);
      }
    }
    .input-box{
      position: relative;
      height: 100rpx;
      width: 100%;
      display: flex;
      justify-content: flex-start;
      align-items: center;
      border-bottom: 1px solid #DCDEE2;
      box-sizing: border-box;
      padding: 0 22rpx;
      margin: 40rpx 0;
      icon{
        font-size: 26rpx;
        color: #04a9f5;
        line-height: 100rpx;
      }
      input{
        color: #595959;
        font-size: 24rpx;
        margin-left: 20rpx;
        line-height: 100rpx;
        height: 100rpx;
        flex: 1;
      }
    }
  }
}
</style>
