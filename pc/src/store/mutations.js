export default {
  LOGIN_IN (state, loginData) {
    state.loginData = loginData
    state.UserToken = loginData.token
  },
  LOGIN_OUT (state) {
    state.loginData = ''
    state.UserToken = ''
  },
  toggleNavCollapse (state) {
    state.isSidebarNavCollapse = !state.isSidebarNavCollapse
  },
  setCrumbList (state, list) {
    state.crumbList = list
  },
  changeMode (state) {
    state.PageMode = state.PageMode === 1 ? 0 : 1
  },
  addTab (state, data) {
    let title = data.title
    let add = true
    state.Tab.list.forEach(e => {
      if (title === e.title) {
        add = false
      }
    })
    if (state.Tab.list.length > 15) {
      // alert('tab不能超过16个')
      state.Tab.list.splice(state.Tab.list.length - 1, 1)
    }
    if (add) {
      state.Tab.list.push(data)
    }
  },
  clearTab (state) {
    state.Tab.list = []
  },
  SetTabList (state, data) {
    state.Tab.list = data
  },
  RemoveTab (state, name) {
    let res
    state.Tab.list.forEach((e, index) => {
      if (e.name === name) {
        res = index
      }
    })
    if (res !== undefined) state.Tab.list.splice(res, 1)
  },
  CruTab (state, data) {
    state.Tab.cru = data
  },
  changeTab (state) {
    state.TabPage = state.TabPage === 1 ? 0 : 1
    state.Tab.list = []
  }
}
