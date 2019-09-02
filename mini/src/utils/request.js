import common from './common'
import CONFIG from './config'
const Fly = require('flyio/dist/npm/wx')
const fly = new Fly()
// 添加拦截器
fly.interceptors.request.use((request) => {
  request.timeout = 10000
  return request
})
// 添加响应拦截器
fly.interceptors.response.use(
  (response) => {
    if(response.data.code !== 1) {
      common.error_tip(response.data.msg)
    }
    return response // 请求成功之后将返回值返回
  },
  (err) => {
    // 请求出错，根据返回状态码判断出错原因
    console.log(err)
    common.error_tip(err.msg)
    return err
  }
)
// 配置请求基地址
fly.config.baseURL = CONFIG.API_PATH
/**
 * 接口请求方法
 * @param param 请求的参数 json格式
 * @param sucCallBack 请求成功的回调
 * @param failCallBack  请求失败的回调
 * @param LoadingStr 是否显示loading层，默认不展示 值为展示内容  false不展示，其余情况展示
 */
const proxy = (fun, param, sucCallBack, failCallBack, LoadingStr) => {

  if (!param) return false;
  let body = {}
  let showLoading = LoadingStr !== false; //是否展示加载层
  if (showLoading) {
    common.loading_tip(LoadingStr || '加载中...');
  }
  body['token'] = common.getToken();
  Object.keys(param).forEach(key => {
    body[key] = param[key]
  })
  fly.post(fun, body).then(res => {
    showLoading && common.close_toast()
    let data = res.data
    let status = parseInt(data.code)
    if (status === 1) { // 接口请求成功
      return typeof sucCallBack === 'function' ? sucCallBack(data) : common.success_tip(data.msg);
    } else if (status === 0) { // 接口请求失败
      return typeof failCallBack === 'function' ? failCallBack(data) : common.error_tip(data.msg);
    } else if (status === -998) { //token失效
      return wx.redirectTo({ url: '/pages/common/login/main' });
    }
  }).catch(err => {
    showLoading && common.close_toast()
    common.error_tip('系统繁忙，请稍后再试');
  })
}
export default proxy
