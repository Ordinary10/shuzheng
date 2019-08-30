import Vuex from 'Vuex'
import Vue from 'vue'
import actions from './actions'
import state from './state'
import mutations from './mutations'
Vue.use(Vuex)
export default new Vuex.Store({
  // 设置属性
  state,
  // 获取属性的状态
  getters: {
    // 获取登录状态
    isLogin: state => state.isLogin
  },
  // 设置属性状态
  mutations,
  // 应用mutations
  actions
})
