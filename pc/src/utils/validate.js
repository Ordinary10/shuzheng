var regId = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/
var tel = /^1[345789]\d{9}$/
const validate = {
  regId: (rule, value, callback) => {
    // 身份证的验证规则
    if (!value) {
      return callback(new Error('身份证不能为空'))
    }
    if (!regId.test(value)) {
      callback(new Error('请输入正确的二代身份证号码'))
    } else {
      callback()
    }
  },
  phone: (rule, value, callback) => {
    if (!value) {
      return callback(new Error('电话号码不能为空'))
    }
    if (!tel.test(value)) {
      callback(new Error('请输入正确的电话号码'))
    } else {
      callback()
    }
  },
  // 验证是否为正整数
  Znumber: (rule, value, callback) => {
    // console.log(value)
    if (!value) {
      return callback(new Error('必输项不能为空'))
    }
    if (!Number(value)) {
      callback(new Error('请输入非零正整数'))
    } else if (!(/(^[1-9]\d*$)/.test(value))) {
      callback(new Error('请输入非零正整数'))
    } else {
      callback()
    }
  }

}

export default validate
