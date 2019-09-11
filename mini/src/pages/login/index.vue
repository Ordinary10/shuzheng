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
    onShow(){
      this.autoLogin()
    },
    mounted(){
    },
    methods:{
      /*自动登录*/
      autoLogin(){
        const _this = this
        wx.login({
          success:function (res) {
            _this.$ajax('login/mimiProgramLogin',{code:res.code},function (res) {
              if(res.code !== 1) {
                common.error_tip(res.msg)
                return
              }
              _this.$store.commit('setToken', res.data.token)
              _this.$store.commit('setRole', res.data.show_page.role)
              _this.$common.normal_tip('自动登录成功')
              if(res.data.show_page.role === 'chef'){
                wx.switchTab({
                  url:'/pages/outbound/main'
                })
              } else {
                wx.switchTab({
                  url:'/pages/procurement/main'
                })
              }
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
              if(res.code === 1){
                _this.$store.commit('setToken', res.data.token)
                _this.$store.commit('setRole', res.data.show_page.role)
                if(res.data.show_page.role === 'chef'){
                  wx.switchTab({
                    url:'/pages/outbound/main'
                  })
                } else {
                  wx.switchTab({
                    url:'/pages/procurement/main'
                  })
                }
              }
            },function (res) {
              _this.$common.normal_tip(res.msg)
            },'登陆中。。。')
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
    height: 705rpx;
  }
  .login-form{
    padding: 60rpx 94rpx 0;
    .login_btn{
      width:556rpx;
      font-size: 36rpx;
      color: white;
      line-height: 80rpx;
      background:#EC181F;
      box-shadow:0px 7px 15px 3px rgba(236, 31, 24, 0.35);
      border-radius:40rpx;
      margin-top: 100rpx;
      &:hover{
        background: #EC5B5A;
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
      margin: 30rpx 0;
      icon{
        font-size: 26rpx;
        color: #EC181F;
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
