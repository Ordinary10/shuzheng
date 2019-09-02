/* 需要权限判断的路由 */
const dynamicRoutes = [
  {
    path: '/system',
    component: () => import('@/pages/system-manage'),
    name: 'system-manage',
    meta: {
      name: '系统管理',
      icon: 'iconxitongguanli'
    },
    children: [
      {
        path: 'user',
        name: 'user',
        component: () => import('@/pages/system-manage/user'),
        meta: {
          name: '用户管理'
        }
      },
      {
        path: 'company',
        name: 'company',
        component: () => import('@/pages/system-manage/company'),
        meta: {
          name: '公司管理'
        }
      },
      {
        path: 'role',
        name: 'role',
        component: () => import('@/pages/system-manage/role'),
        meta: {
          name: '角色管理'
        }
      }
    ]
  }
]
export default dynamicRoutes
