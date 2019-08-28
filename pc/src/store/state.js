export default {
  get UserToken () {
    return sessionStorage.getItem('token')
  },
  set UserToken (value) {
    sessionStorage.setItem('token', value)
  },
  get loginData () {
    let loginData = sessionStorage.getItem('loginData')
    return JSON.parse(loginData)
  },
  set loginData (value) {
    sessionStorage.setItem('loginData', JSON.stringify(value))
  },
  /* 导航菜单是否折叠 */
  isSidebarNavCollapse: false,
  /* 面包屑导航列表 */
  crumbList: [],
  // 页面布局类型
  // PageMode: 1 导航栏在上  0在左侧
  get PageMode () {
    if (sessionStorage.getItem('PageMode') === null) {
      sessionStorage.setItem('PageMode', 1)
    }
    return +sessionStorage.getItem('PageMode')
  },
  set PageMode (value) {
    sessionStorage.setItem('PageMode', value)
  },
  get TabPage () {
    if (sessionStorage.getItem('TabPage') === null) {
      sessionStorage.setItem('TabPage', 0)
    }
    return +sessionStorage.getItem('TabPage')
  },
  set TabPage (value) {
    sessionStorage.setItem('TabPage', value)
  },
  // tab模式下数据
  Tab: {
    list: [],
    cru: ''
  }

}
