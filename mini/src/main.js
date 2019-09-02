import Vue from 'vue'
import App from './App'
import request from '@/utils/request'
import store from '@/store/index'
import common from '@/utils/common'
import './assets/styles.scss'
Vue.config.productionTip = false
App.mpType = 'app'
Vue.prototype.$ajax = request
Vue.prototype.$store = store
Vue.prototype.$common = common
const app = new Vue(App)
app.$mount()
