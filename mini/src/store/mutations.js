export default {
  // 保存权限
  setRole(state, role) {
    state.role = role
  },
  // 保存登录状态
  setToken(state, token) {
    state.token = token
  },
  // 保存内容高度(rpx)
  setClientHeight(state, clientHeight) {
    state.clientHeight = clientHeight
  }
}
