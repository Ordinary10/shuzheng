import Vue from 'vue'
import App from './App'
import request from '@/utils/request'
import store from '@/store/index'
import './assets/style.scss'
import components from '@/components/index'
import common from '@/utils/common'
/*全局组件注册*/
Object.keys(components).forEach(key => {
  Vue.component(key, components[key])
})
Vue.config.productionTip = false
App.mpType = 'app'
Vue.config.productionTip = false
App.mpType = 'app'
Vue.prototype.$ajax = request
Vue.prototype.$store = store
Vue.prototype.$common = common
const app = new Vue(App)
app.$mount()
