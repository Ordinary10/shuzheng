import store from '../store'
import axios from 'axios'
import qs from 'qs'
import { Modal,Message, Spin, Icon } from 'iview'
import common from './common'

// 创建axios实例
const service = axios.create({
  headers: {
    'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8',
    'Accept': '*/*'
  },
  // baseURL: common.API_PATH, // api的base_url
  timeout: 15000 // 请求超时时间
})
// request拦截器
service.interceptors.request.use(config => {
  return config
}, error => {
  console.log(error)
  return Promise.reject(error)
})

// respone拦截器
service.interceptors.response.use(
  response => {
    /* res.status不为1直接错误提示 */
    const res = response.data
    if (res.code === 1) {
      Spin.hide()
      return res
    } else if (res.code === -998) {
      Message.error({
        content: res.msg,
        duration: 5
      })
      Spin.hide()
      setTimeout(e=>{
        store.commit('LOGIN_OUT')
      },1000)
    } else {
      Message.error({
        content: res.msg,
        duration: 5
      })
      Spin.hide()
      return res
    }
  },
  error => {
    console.log('err' + error)// for debug
    Message.error({
      content: error.message,
      duration: 5
    })
    Spin.hide()
    return Promise.reject(error)
  }
)
/* 表格的通用数据请求方法 */
export function tableRequest (options, data, Loadings) {
  if (!Loadings) {
    Spin.show({
      render: (h) => {
        return h('div', [
          h('Icon', {
            'class': 'demo-spin-icon-load',
            props: {
              type: 'ios-loading',
              size: 30,
              name: 'iconLoading'
            }
          }),
          h('div', 'Loading')
        ])
      }
    })
  }
  data.limit = options.limit || 20
  data.page = options.page || 1
  data.token = window.sessionStorage.getItem('token') || ''
  return service({
    'method': 'post',
    'data': qs.stringify(data),
    'url': `${common.API_PATH}/${options.fun}`
  })
}
/* 其他数据请求的通用方法 */
export function requests (fun, data, Loadings) {
  if (!Loadings) {
    Spin.show({
      render: (h) => {
        return h('div', [
          h('Icon', {
            'class': 'demo-spin-icon-load',
            props: {
              type: 'ios-loading',
              size: 30,
              name: 'iconLoading'
            }
          }),
          h('div', 'Loading')
        ])
      }
    })
  }
  data.token = window.sessionStorage.getItem('token') || ''
  return service({
    'method': 'post',
    'data': qs.stringify(data),
    'url': `${common.API_PATH}/${fun}`
  })
}

/* 其他数据请求的通用方法 */
export function no_long_request (fun, data) {
  data.token = window.sessionStorage.getItem('token') || ''
  return service({
    'method': 'post',
    'data': data,
    'url': `${common.API_PATH}/${fun}`
  })
}
/* 带有成功失败提示的 */
export function request  (fun, data) {
  return new Promise(function (resolve, reject) {

    data.token = window.sessionStorage.getItem('token') || ''

    // 执行异步ajax请求
    let promise
      // 发送get请求
      promise = service({
        'method': 'post',
        'data': qs.stringify(data),
        'url': `${common.API_PATH}/${fun}`
      })

    promise.then(function (response) {
      // 成功了调用resolve()
      console.log(123)
      resolve(response)
      // console.log(123)
    }).catch(function (error) {
      //失败了调用reject()
      reject(error)
    })
  })


}

