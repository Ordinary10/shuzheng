// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store'
import { request } from './utils/request'
import iView from 'iview'
import 'iview/dist/styles/iview.css'
import common from './utils/common'
import './assets/index.scss'
import globalComponents from './components'
import filters from './utils/global_filter'
// 引入图片查看器
import Viewer from 'v-viewer'
import 'viewerjs/dist/viewer.css'
Vue.use(Viewer)
Viewer.setDefaults({
  // Options: { 'inline': true, 'button': true, 'navbar': true, 'title': true, 'toolbar': true, 'tooltip': true, 'movable': true, 'zoomable': true, 'rotatable': true, 'scalable': true, 'transition': true, 'fullscreen': true, 'keyboard': true, 'url': 'data-source', show: () => { this.update() } }
})
// 全局过滤器
Object.keys(filters).forEach(key => {
  Vue.filter(key, filters[key])
})
// 全局组件
Object.keys(globalComponents).forEach(key => {
  Vue.component(key, globalComponents[key])
})
// 原型上注册通用请求方法
Vue.prototype.$axios = request
// 原型上注册全局对象
Vue.prototype.$common = common
Vue.use(iView)
Vue.config.productionTip = false
/* eslint-disable no-new */
router.beforeEach((to, from, next) => {
  if (!store.state.UserToken) {
    if (
      to.matched.length > 0 &&
      !to.matched.some(record => record.meta.requiresAuth)
    ) {
      next()
    } else {
      next({ path: '/login' })
    }
  } else {
    if (!store.state.permission.permissionList) {
      store.dispatch('permission/FETCH_PERMISSION').then(() => {
        next({ path: to.path })
      })
    } else {
      if (to.path !== '/login') {
        next()
      } else {
        next(from.fullPath)
      }
    }
  }
})

router.afterEach((to, from, next) => {
  if (store.state.TabPage === 1) {
  } else {
    var routerList = to.matched
    store.commit('setCrumbList', routerList)
    store.commit('permission/SET_CURRENT_MENU', to.name)
  }
})
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})
