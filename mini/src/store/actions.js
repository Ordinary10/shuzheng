export default {
  action_setRole ({ commit }, role) {
    // 保存权限
    commit('setRole', role)
  },
  action_setToken ({ commit }, token) {
    // 保存token
    commit('setToken', token)
  }
}
