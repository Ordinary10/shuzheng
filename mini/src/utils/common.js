// 公共方法
import CONFIG from './config'
import tabBarRoleList from './tabBarRoleList'
const {$Toast} = require('../../static/dist/base/index')
import state from '@/store/state'

const common = {
  /**
   * 获取url的get参数
   * @param key 键名，为空则返回所有的
   * @param default_value 默认值
   */
  getQueryParam: (key, default_value) => {
    let pages = getCurrentPages() // 获取加载的页面
    let currentPage = pages[pages.length - 1] // 获取当前页面的对象
    // let url = currentPage.route;                    //当前页面url
    let options = currentPage.options // 如果要获取url中所带的参数可以查看options
    return !key ? options : (key in options ? options[key] : default_value)
  },

  /**
   * 各种提示层，调用该方法需在vue页面中添加 <i-toast id="toast" />
   * @param msg 提示语
   * @param callBack 提示完成后的回调
   * @param selector toast的ID，默认为toast
   */
  success_tip: (msg, callBack, selector) => {
    msg = msg || 'success！'
    common.normal_tip(msg, selector, 'success')
    if (typeof callBack === 'function') {
      //如果有回调方法则提示语显示一秒后执行回调函数
      setTimeout(() => {
        common.close_toast();
        callBack();
      }, 1000);
    }
  },

  error_tip: (msg, selector) => {
    msg = msg || 'error！'
    common.normal_tip(msg, selector, 'error')
  },
  // 加载层默认不关闭
  loading_tip: (msg, selector) => {
    msg = msg || '加载中...'
    common.normal_tip(msg, selector, 'loading', 0, false)
  },
  // 关闭提示框
  close_toast: (selector) => {
    $Toast.hide(selector || '#toast')
  },
  /**
   *
   * @param msg
   * @param selector
   * @param type
   * @param time 显示时间
   * @param clickCancel  是否显示遮罩层，点击会关闭 默认为true
   */
  normal_tip: (msg, selector, type, time, clickCancel) => {
    type = type || 'default'
    time = time === 0 ? 0 : CONFIG.TIP_TIME
    selector = selector || '#toast'
    $Toast({
      content: msg,
      type: type,
      selector: selector,
      duration: time,
      mask: !!clickCancel
    })
  },

  /**
   * 文件上传
   * @param filePath 文件路径
   * @param fun 请求的方法
   * @param param 请求的参数
   * @param success 成功的回调
   * @param fail  失败的回调
   */
  uploadFile: (filePath, fun, param, success, fail) => {
    param['x-token'] = common.getToken()
    wx.uploadFile({
      url: CONFIG.API_PATH + fun,
      name: 'file',
      filePath: filePath,
      header: {
        'Content-Type': 'multipart/form-data'
      },
      formData: param,
      success: function (res) {
        success(JSON.parse(res.data))
      },
      fail: function (res) {
        return typeof fail === 'function' ? fail(res) : false
      },
    })
  },
  checkMobile: (phone) => {
    var myreg = /^[1][0-9]{10}$/
    if (!myreg.test(phone)) {
      return false
    } else {
      return true
    }
  },
  //小程序页面跳转
  jump_to(url) {
    wx.navigateTo({url: url})
  },
  //页面后退,number返回的页面数，如果大于现有页面数，则返回到首页
  back_to(number) {
    number = number ? number : 1;
    wx.navigateBack({delta: number})
  },
  //刷新当前页面
  pageReload() {
    let pages = getCurrentPages() // 获取加载的页面
    console.log(pages)
    /*if(pages.length > 0){
      pages[pages.length - 1].onLoad()
    }*/
  },
  getNowFormatDate() {
    let date = new Date();
    let seperator1 = "-";
    let year = date.getFullYear();
    let month = date.getMonth() + 1;
    let strDate = date.getDate();
    if (month >= 1 && month <= 9) {
      month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
      strDate = "0" + strDate;
    }
    return year + seperator1 + month + seperator1 + strDate;
  },
  getRole() {
    let roleName = state.role || 'admin'
    return tabBarRoleList[roleName]
  },
  getToken() {
    return state.token || ''
  }

}

export default common
