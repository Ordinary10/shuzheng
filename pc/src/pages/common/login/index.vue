<template>
    <div class="main-container">
      <div class="login-form">
        <div class="login-face">
        </div>
        <form action="" ref="form" class="form">
          <div class="form-item">
            <span class="form-label" :class="{'blur-label-style': accountFocus}">用户名</span>
            <input class="form-input" ref="account" :class="{'blur-input-style': accountFocus}" type="text" @focus="inputFocus('account')" @blur="inputBlur('account')" v-model="loginForm.account" :placeholder="placeholder_account">
          </div>
          <div class="form-item">
            <span class="form-label" :class="{'blur-label-style': pwdFocus}">密码</span>
            <input class="form-input" ref="pwd" :class="{'blur-input-style': pwdFocus}" type="password" @focus="inputFocus('pwd')" @blur="inputBlur('pwd')" v-model="loginForm.pwd" :placeholder="placeholder_pwd">
          </div>
          <div class="form-item">
            <span class="form-label" :class="{'blur-label-style': codeFocus}">验证码</span>
            <input class="form-input" ref="code" :class="{'blur-input-style': codeFocus}" type="text" @focus="inputFocus('code')" @blur="inputBlur('code')" v-model="loginForm.code" :placeholder="placeholder_code" @keydown="keyDown">
            <img class="code-img" :src="codeImgSrc" alt="" @click="getCode">
          </div>
          <div class="form-item">
            <button type="button" class="login-btn" @click="login">登录</button>
          </div>
        </form>
      </div>
    </div>
</template>

<script>
export default {
  name: 'index',
  data () {
    return {
      codeImgSrc: '',
      placeholder_account: '',
      placeholder_pwd: '',
      placeholder_code: '',
      accountFocus: false,
      pwdFocus: false,
      codeFocus: false,
      loginForm: {
        account: '',
        pwd: '',
        code: '',
        loginRandom: ''
      }
    }
  },
  created () {
    this.getCode()
  },
  methods: {
    /* 登录 */
    async login () {
      const _this = this
      if (_this.loginForm.account === '') {
        this.$Message.error({
          content: '必填项不能为空',
          duration: 5
        })
        this.$refs.account.focus()
      } else if (_this.loginForm.pwd === '') {
        this.$Message.error({
          content: '必填项不能为空',
          duration: 5
        })
        this.$refs.pwd.focus()
      } else if (_this.loginForm.code === '') {
        this.$Message.error({
          content: '必填项不能为空',
          duration: 5
        })
        this.$refs.code.focus()
      } else {
        let res
        let pagededa
        let loginData
        res = await _this.$axios('login/doLogin', _this.loginForm)
        if (res.status === 1) {
          loginData = await _this.$store.dispatch('LOGIN_IN', res.data)
          pagededa = await _this.$axios('Common/getPageInitInfo', {type: [
            'order_state',
            'company_info',
            'role_info',
            'plate_type',
            'car_version',
            'ware_house',
            'car_status',
            'house_info',
            'charge_type',
            'remit_type',
            'car_service',
            'car_type',
            'insurance_type',
            'order_nature',
            'collection_type',
            'driver_manage',
            'financial_plan',
            'repair_shop'
          ]}, 'no')
          if (pagededa.status === 1) {
            sessionStorage.setItem('pageInitInfo', JSON.stringify(pagededa.data))
            _this.$router.push('/home')
          }
        } else if (res.msg === '验证码错误！') {
          _this.getCode()
        }
      }
    },
    /* 获取验证码 */
    getCode () {
      const _this = this
      let randomNo = Math.random()
      let norand = _this.MathRand()
      _this.loginForm.loginRandom = norand
      _this.codeImgSrc = `${_this.$common.API_PATH}?fun=login/captcha&random=${randomNo}&loginRandom=${norand}`
    },
    /* 获取验证码随机参数生成 */
    MathRand () {
      let Num = ''
      for (var i = 0; i < 6; i++) {
        Num += Math.floor(Math.random() * 10)
      }
      return Num
    },
    /* 验证码输入框回车键触发login */
    keyDown (e) {
      if (e.key === 'Enter') {
        this.login()
      }
    },
    /* 输入框的聚焦和失焦 */
    inputFocus (type) {
      switch (type) {
        case 'account':
          this.accountFocus = true
          this.placeholder_account = '请输入用户名'
          break
        case 'pwd':
          this.pwdFocus = true
          this.placeholder_pwd = '请输入密码'
          break
        case 'code':
          this.codeFocus = true
          this.placeholder_code = '请输入验证码'
          break
      }
    },
    inputBlur (type) {
      switch (type) {
        case 'account':
          this.accountFocus = false
          this.placeholder_account = ''
          break
        case 'pwd':
          this.pwdFocus = false
          this.placeholder_pwd = ''
          break
        case 'code':
          this.codeFocus = false
          this.placeholder_code = ''
          break
      }
    }
  }
}
</script>

<style scoped lang="scss">
  .blur-label-style.form-label{
    color: red !important;
    font-size: 12px !important;
  }
  .blur-input-style.form-input{
    border:1px solid red !important;
  }
  .main-container{
    height: 100%;
    background: url("../../../../static/images/login_bg.jpg") no-repeat center center;
    background-size: 100% 100%;
    position: relative;
    #loginForm{
      width: 500px;
      margin: 0 auto;
    }
    .login-form{
      padding: 0 20px;
      width: 300px;
      height: 335px;
      position: absolute;
      left: 80%;
      top: 50%;
      margin: -150px 0 0 -150px;
      -webkit-box-sizing: border-box;
      -moz-box-sizing: border-box;
      -o-box-sizing: border-box;
      box-sizing: border-box;
      background: #fff;
      -webkit-border-radius: 5px;
      -moz-border-radius: 5px;
      border-radius: 5px;
      box-shadow: 0 0 50px #009688;
      .login-face{
        background: url("../../../../static/images/face.jpg") no-repeat center center;
        background-size: 100% 100%;
        width: 110px;
        height: 110px;
        position: absolute;
        border-radius: 50%;
        box-shadow: 0 0 30px #009688;
        border: 5px solid #fff;
        top: -50px;
        left: 50%;
        transform: translateX(-50%);
      }
      .form{
        margin-top: 76px;
        .form-item{
          height: 38px;
          margin-bottom: 16px;
          position: relative;
          .form-input{
            width: 100%;
            padding-left: 10px;
            border: 1px solid #e6e6e6;
            height: 38px;
            line-height: 1.3;
            border-radius: 2px;
            &:hover{
              border-color: #D2D2D2;
            }
          }
          .code-img{
            position: absolute;
            width: 50%;
            height: 36px;
            right: 1px;
            top: 1px;
          }
          .form-label{
            position: absolute;
            color: #757575;
            left: 10px;
            top: -10px;
            line-height: 20px;
            background: #fff;
            padding: 0 5px;
            font-size: 14px;
            cursor: text;
          }
          .login-btn{
            width: 100%;
            display: inline-block;
            height: 38px;
            line-height: 38px;
            padding: 0 18px;
            background-color: #009688;
            color: #fff;
            white-space: nowrap;
            text-align: center;
            font-size: 14px;
            border: none;
            border-radius: 2px;
            cursor: pointer;
            &:hover{
              opacity: .8;
              color: #fff;
              border: none !important;
            }
          }
        }
      }
    }
  }
</style>
