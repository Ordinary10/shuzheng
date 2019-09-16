/**
 *
 * @param  {Array} userRouter 后台返回的用户权限json
 * @param  {Array} allRouter  前端配置好的所有动态路由的集合
 * @return {Array} realRoutes 过滤后的路由
 */

export function recursionRouter (userRouter = [], allRouter = []) {
  var realRoutes = []
  userRouter.forEach((v, i) => {
    allRouter.forEach((item, index) => {
      if (v.title === item.meta.name) {
        if (v.children && v.children.length > 0) {
          item.children = recursionRouter(v.children, item.children)
        }
        realRoutes.push(item)
      }
      if (item.meta.pass) {
        realRoutes.push(item)
        item.meta.pass = false
      }
    })
  })
  return realRoutes
}

/**
 *
 * @param {Array} routes 用户过滤后的路由
 *
 * 递归为所有有子路由的路由设置第一个children.path为默认路由
 */
export function setDefaultRoute (routes) {
  routes.forEach((v, i) => {
    if (v.children && v.children.length > 0) {
      v.redirect = { name: v.children[0].name }
      setDefaultRoute(v.children)
    }
  })
}
